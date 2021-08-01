#!/bin/sh

#### Vars ####

export LOC="/var/www/html/jars"

#### DEBUG ####

cd "/root/BuildTools"
php Debug.php

rm -rf ./work

#### Updating BuildTools ####

rm BuildTools.jar
rm BuildTools.log.txt
echo "Deleted BuildTools.jar"
echo "Downloading a new BuildTools.jar"
curl -o BuildTools.jar "https://hub.spigotmc.org/jenkins/job/BuildTools/lastSuccessfulBuild/artifact/target/BuildTools.jar"
echo "BuildTools.jar has been downloaded."

#### Retrieving Latest Vanilla Version ####

echo "Checking version information."
export VERSIONS_JSON=https://launchermeta.mojang.com/mc/game/version_manifest.json
export VANILLA_VERSION=`curl -fsSL $VERSIONS_JSON | jq -r '.latest.release'`
export SNAPSHOT_VERSION=`curl -fsSL $VERSIONS_JSON | jq -r '.latest.snapshot'`

echo "$VANILLA_VERSION is the latest vanilla version."
echo "$SNAPSHOT_VERSION is the latest snapshot version."

#### Downloading Latest Jars ####

echo "Downloading Jars ..."

#### Vanilla ####

echo "Downloading Vanilla ..."
versionManifestUrl=$(curl -fsSL $VERSIONS_JSON | jq --arg VANILLA_VERSION "$VANILLA_VERSION" --raw-output '[.versions[]|select(.id == $VANILLA_VERSION)][0].url')

vanillaDownloadUrl=$(curl -fsSL ${versionManifestUrl} | jq --raw-output '.downloads.server.url')

vanillaLocation=$LOC/vanilla/vanilla/vanilla-$VANILLA_VERSION.jar

if [ ! -f $vanillaLocation ] || [ $(curl -sI "$vanillaDownloadUrl" | awk '/Content-Length/{gsub("\\r", ""); print $2}') -ne $(stat -c%s $vanillaLocation) ]; then
  curl -f -o $vanillaLocation $vanillaDownloadUrl
else
  echo Already up to date!
fi

#### Snapshot ####

echo "Downloading Snapshot ..."
versionManifestUrl=$(curl -fsSL $VERSIONS_JSON | jq --arg SNAPSHOT_VERSION "$SNAPSHOT_VERSION" --raw-output '[.versions[]|select(.id == $SNAPSHOT_VERSION)][0].url')

snapshotDownloadUrl=$(curl -fsSL ${versionManifestUrl} | jq --raw-output '.downloads.server.url')
snapshotId=$(curl -fsSL ${versionManifestUrl} | jq --raw-output '.id')
snapshotAssetVersion=$(curl -fsSL ${versionManifestUrl} | jq --raw-output '.assetIndex.id')

if [[ "$snapshotId" == *"$snapshotAssetVersion"* ]]; then
  snapshotLocation=$LOC/vanilla/snapshot/snapshot-$snapshotId.jar
else
  snapshotLocation=$LOC/vanilla/snapshot/snapshot-$snapshotAssetVersion-$snapshotId.jar
fi

if [ ! -f $snapshotLocation ] || [ $(curl -sI "$snapshotDownloadUrl" | awk '/Content-Length/{gsub("\\r", ""); print $2}') -ne $(stat -c%s $snapshotLocation) ]; then
  curl -f -o $snapshotLocation $snapshotDownloadUrl
else
  echo Already up to date!
fi

##### Spigot #####

echo "Starting Spigot Builds..."

java -jar BuildTools.jar --compile spigot,craftbukkit --rev $VANILLA_VERSION
cd "/root/BuildTools/"

spigotLocation=$LOC/servers/spigot/spigot-$VANILLA_VERSION.jar

if [ ! -f $spigotLocation ] || [ $(stat -c%s spigot-$VANILLA_VERSION.jar) -ne $(stat -c%s $spigotLocation) ]; then
  mv spigot-$VANILLA_VERSION.jar $LOC/servers/spigot/
else
  echo Already up to date!
  rm -r spigot-$VANILLA_VERSION.jar    
fi

#### Bukkit ####

bukkitLocation=$LOC/servers/bukkit/bukkit-$VANILLA_VERSION.jar
bukkitBuildLocation=./CraftBukkit/target/craftbukkit-$VANILLA_VERSION-R0.1-SNAPSHOT.jar

if [ ! -f $bukkitLocation ] || [ $(stat -c%s $bukkitBuildLocation) -ne $(stat -c%s $bukkitLocation) ]; then
  mv $bukkitBuildLocation $bukkitLocation
else
  echo Already up to date!
fi

#### Paper ####

echo "Starting Paper Builds..."

paperUrl=https://papermc.io/api/v1/paper/$VANILLA_VERSION/latest/download
curl -f -o paper.jar $paperUrl
paperLocation=$LOC/servers/paper/paper-$VANILLA_VERSION.jar

if [ ! -f $paperLocation ] || [ $(stat -c%s paper.jar) -ne $(stat -c%s $paperLocation) ]; then
	curl -f -o $paperLocation $paperUrl
else
  echo Already up to date!
fi
rm -r paper.jar

#### Magma ####

echo "Starting Magma Builds..."

export MAGMA_JSON=https://api.magmafoundation.org/api/resources/magma/latest/download

magmaUrl=$(curl -fsSL $MAGMA_JSON | jq --raw-output '.browser_download_url')
curl -fL -o magma.jar $magmaUrl
magmaLocation=$LOC/more/magma/magma-1.12.2.jar

if [ ! -f $magmaLocation ] || [ $(stat -c%s magma.jar) -ne $(stat -c%s $magmaLocation) ]; then
	curl -fL -o $magmaLocation $magmaUrl
else
  echo Already up to date!
fi
rm -r mamga.jar

#### Mohist ####

echo "Starting Mohist Builds..."

export mohistUrl=https://ci.codemc.io/job/Mohist-Community/job/Mohist-1.12.2/lastSuccessfulBuild/artifact/*zip*/archive.zip
curl -f -o /root/mohist/mohist.zip $mohistUrl
unzip /root/mohist/mohist.zip -d /root/mohist
mv /root/mohist/archive/build/distributions/*.jar /root/mohist/archive/build/distributions/mohist-1.12.2.jar

mohistJar=$LOC/more/mohist/mohist-1.12.2.jar

if [ $(stat -c%s /root/mohist/archive/build/distributions/*.jar) -ne $(stat -c%s $mohistJar) ]; then
	mv /root/mohist/archive/build/distributions/*.jar $LOC/more/mohist/
else
  echo Already up to date!
fi
rm -r /root/mohist/*

#### NukkitX ####

echo "Starting NukkitX Builds..."

nukkitUrl=https://ci.nukkitx.com/job/NukkitX/job/Nukkit/job/master/lastSuccessfulBuild/artifact/target/nukkit-1.0-SNAPSHOT.jar
curl -f -o nukkit.jar $nukkitUrl
nukkitJar=$LOC/bedrock/nukkitx/nukkitx-1.14.jar

if [ ! -f $nukkitJar ] || [ $(stat -c%s nukkit.jar) -ne $(stat -c%s $nukkitJar) ]; then
	curl -f -o $nukkitJar $nukkitUrl
else
	echo Already up to date!
fi
rm -r nukkit.jar

#### Pocketmine ####

echo "Starting Pocketmine Builds..."

pocketmineUrl=https://jenkins.pmmp.io/job/PocketMine-MP/lastSuccessfulBuild/artifact/PocketMine-MP.phar
curl -f -o pocketmine.phar $pocketmineUrl
pocketmineJar=$LOC/bedrock/pocketmine/pocketmine-1.14.phar

if [ ! -f $pocketmineJar ] || [ $(stat -c%s pocketmine.phar) -ne $(stat -c%s $pocketmineJar) ]; then
	curl -f -o $pocketmineJar $pocketmineUrl
else
	echo Already up to date!
fi
rm -r pocketmine.phar

#### Waterfall ####

echo "Starting Waterfall Builds..."

waterfallUrl=https://papermc.io/api/v1/waterfall/1.16/latest/download
curl -f -o waterfall.jar $waterfallUrl
waterfallJar=$LOC/proxies/waterfall/waterfall-1.16.jar

if [ ! -f $waterfallJar ] || [ $(stat -c%s waterfall.jar) -ne $(stat -c%s $waterfallJar) ]; then
	curl -f -o $waterfallJar $waterfallUrl
        cp $waterfallJar $LOC/proxies/waterfall/waterfall-1.15.jar
	cp $waterfallJar $LOC/proxies/waterfall/waterfall-1.14.jar
	cp $waterfallJar $LOC/proxies/waterfall/waterfall-1.13.jar
	cp $waterfallJar $LOC/proxies/waterfall/waterfall-1.12.jar
	cp $waterfallJar $LOC/proxies/waterfall/waterfall-1.11.jar
	cp $waterfallJar $LOC/proxies/waterfall/waterfall-1.10.jar
	cp $waterfallJar $LOC/proxies/waterfall/waterfall-1.9.jar
	cp $waterfallJar $LOC/proxies/waterfall/waterfall-1.8.jar
else
	echo Already up to date!
fi
rm -r waterfall.jar

#### Travertine ####

echo "Starting Travertine Builds..."

travertineUrl=https://papermc.io/api/v1/travertine/1.15/latest/download
curl -f -o travertine.jar $travertineUrl
travertineJar=$LOC/more/travertine/travertine-1.16.jar

if [ ! -f $travertineJar ] || [ $(stat -c%s travertine.jar) -ne $(stat -c%s $travertineJar) ]; then
	curl -f -o $travertineJar $travertineUrl
        cp $travertineJar $LOC/more/travertine/travertine-1.15.jar
	cp $travertineJar $LOC/more/travertine/travertine-1.14.jar
	cp $travertineJar $LOC/more/travertine/travertine-1.13.jar
	cp $travertineJar $LOC/more/travertine/travertine-1.12.jar
	cp $travertineJar $LOC/more/travertine/travertine-1.11.jar
	cp $travertineJar $LOC/more/travertine/travertine-1.10.jar
	cp $travertineJar $LOC/more/travertine/travertine-1.9.jar
	cp $travertineJar $LOC/more/travertine/travertine-1.8.jar
	cp $travertineJar $LOC/more/travertine/travertine-1.7.jar
else
	echo Already up to date!
fi
rm -r travertine.jar

#### Bungee ####

echo "Starting Bungee Builds..."

bungeecordUrl=https://ci.md-5.net/job/BungeeCord/lastSuccessfulBuild/artifact/bootstrap/target/BungeeCord.jar
curl -f -o bungeecord.jar $bungeecordUrl
bungeecordJar=$LOC/proxies/bungeecord/bungeecord-1.16.jar

if [ ! -f $bungeecordJar ] || [ $(stat -c%s bungeecord.jar) -ne $(stat -c%s $bungeecordJar) ]; then
	curl -f -o $bungeecordJar $bungeecordUrl
        cp $bungeecordJar $LOC/proxies/bungeecord/bungeecord-1.15.jar
	cp $bungeecordJar $LOC/proxies/bungeecord/bungeecord-1.14.jar
	cp $bungeecordJar $LOC/proxies/bungeecord/bungeecord-1.13.jar
	cp $bungeecordJar $LOC/proxies/bungeecord/bungeecord-1.12.jar
	cp $bungeecordJar $LOC/proxies/bungeecord/bungeecord-1.11.jar
	cp $bungeecordJar $LOC/proxies/bungeecord/bungeecord-1.10.jar
	cp $bungeecordJar $LOC/proxies/bungeecord/bungeecord-1.9.jar
	cp $bungeecordJar $LOC/proxies/bungeecord/bungeecord-1.8.jar
else
	echo Already up to date!
fi
rm -r bungeecord.jar


#### Velocity ####

echo "Starting Velocity Builds..."

velocityUrl=https://ci.velocitypowered.com/job/velocity/lastSuccessfulBuild/artifact/*zip*/archive.zip
curl -f -o /root/velocity/velocity.zip $velocityUrl
unzip /root/velocity/velocity.zip -d /root/velocity
mv /root/velocity/archive/proxy/build/libs/*.jar /root/velocity/archive/proxy/build/libs/velocity-1.16.jar


velocityJar=$LOC/proxies/velocity/velocity-1.16.jar

if [ $(stat -c%s /root/velocity/archive/proxy/build/libs/*.jar) -ne $(stat -c%s $velocityJar) ]; then
	mv /root/velocity/archive/proxy/build/libs/*.jar $LOC/proxies/velocity/
        cp $velocityJar $LOC/proxies/velocity/velocity-1.15.jar
	cp $velocityJar $LOC/proxies/velocity/velocity-1.14.jar
	cp $velocityJar $LOC/proxies/velocity/velocity-1.13.jar
	cp $velocityJar $LOC/proxies/velocity/velocity-1.12.jar
	cp $velocityJar $LOC/proxies/velocity/velocity-1.11.jar
	cp $velocityJar $LOC/proxies/velocity/velocity-1.10.jar
	cp $velocityJar $LOC/proxies/velocity/velocity-1.9.jar
	cp $velocityJar $LOC/proxies/velocity/velocity-1.8.jar
else
	echo Already up to date!
fi
rm -r /root/velocity/*

#### We're Done, Save The Time ####
curl -g https://serverjars.com/api/updateCache.php?accessKey=jxWq8Z5pc5i6WeHQpU5sRRMCynSmxrYA9kHEpBmMS2sVSLedL7

