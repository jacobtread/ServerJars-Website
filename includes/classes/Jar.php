<?php

class Jar {
    private static $cachePath = __DIR__ . '/../data/jars.json';

    private static function useCache($skipJars = false) {
        if (file_exists(Jar::$cachePath)) {
            $contents = file_get_contents(Jar::$cachePath, true);
            if ($contents !== false) {
                $parsed = json_decode($contents, true);
                if ($parsed !== null) {
                    $expires = $parsed['expires'];
                    if ($expires < time()) {
                        return false;
                    } else {
                        $data = $parsed['data'];
                        if (!$skipJars) {
                            return $data;
                        } else {
                            $newData = [];
                            foreach (array_keys($data) as $mainCategory) {
                                $newData[$mainCategory] = [];
                                foreach (array_keys($data[$mainCategory]) as $childCategory) {
                                    array_push($newData[$mainCategory], $childCategory);
                                }
                            }
                            return $newData;
                        }
                    }
                }
            }
        }
        return false;
    }

    private static function saveCache($data) {
        $expires = time() + 60 * 60 * 10;
        $jsonData = json_encode(['expires' => $expires, 'data' => $data]);
        file_put_contents(Jar::$cachePath, $jsonData);
    }

    public static function getHash($jar) {
        /* TODO: APC Caching */
        return md5_file($jar);
    }

    public static function getTree($type, $data, $acceptMain) {
        foreach (array_keys($data) as $mainCategory) {
            if ($mainCategory === $type && $acceptMain) {
                return $acceptMain;
            }
            foreach (array_keys($data[$mainCategory]) as $subCategory) {
                if ($subCategory === $type) {
                    return $mainCategory;
                }
            }
        }
        return false;
    }

    public static function collect($skipJars = false, $skipCache = false) {
        $jarPaths = __DIR__ . '/../../jars';
        $useCache = Jar::useCache($skipJars);
        if ($useCache === false || $skipCache) {
            $data = [];
            $mainCategories = array_slice(scandir($jarPaths), 2);
            if (sizeof($mainCategories) > 0) {
                foreach ($mainCategories as $mainCategory) {
                    $categoryPath = $jarPaths . '/' . $mainCategory;
                    $subCategories = array_slice(scandir($categoryPath), 2);
                    foreach ($subCategories as $subCategory) {
                        if (!$skipJars) {
                            $jars = [];
                            $subCategoryPath = $categoryPath . '/' . $subCategory;
                            $rawJars = array_slice(scandir($subCategoryPath), 2);
                            usort($rawJars, function ($a, $b) {
                                $ap = strpos($a, '-rc');
                                $bp = strpos($b, '-rc');
                                if ($ap > 0 && $b == false) {
                                    return 1;
                                } else if ($ap == false && $bp > 0) {
                                    return -1;
                                } else if ($ap > 0 && $bp > 0) {
                                    $av = (int)substr($a, $ap + 3, 1);
                                    $bv = (int)substr($b, $bp + 3, 1);
                                    if ($av > $bv) {
                                        return 1;
                                    } else if ($av < $bv) {
                                        return -1;
                                    }
                                }
                                return version_compare($a, $b);
                            });
                            $rawJars = array_reverse($rawJars);
                            foreach ($rawJars as $rawJar) {
                                $version = self::stripName($rawJar, $subCategory);
                                $name = self::getName($rawJar);
                                $jarPath = $subCategoryPath . '/' . $rawJar;
                                if ($name !== false) {
                                    array_push($jars, [
                                        'version' => $version,
                                        'file' => $rawJar,
                                        'md5' => self::getHash($jarPath),
                                        'built' => filectime($jarPath)
                                    ]);
                                }
                            }
                            $data[$mainCategory][$subCategory] = $jars;
                        } else {
                            if (!isset($data[$mainCategory])) {
                                $data[$mainCategory] = [];
                            }
                            array_push($data[$mainCategory], $subCategory);
                        }
                    }
                }
            }
            if (sizeof($data) > 0 && !$skipJars) {
                self::saveCache($data);
            }
            return $data;
        } else {
            return $useCache;
        }
    }

    private static function getName($name) {
        $firstIndex = strpos($name, '-');
        if ($firstIndex == -1) {
            return false;
        }
        return substr($name, 0, $firstIndex);
    }

    private static function stripName($name, $subCategory) {
        $strings = [
            $subCategory,
            '.jar',
            '.phar',
            '-'
        ];
        foreach ($strings as $string) {
            $name = str_replace($string, '', $name);
        }
        return $name;
    }


}