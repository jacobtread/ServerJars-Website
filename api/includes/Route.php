<?php


class Route {

    const ADMIN_KEY = 'jxWq8Z5pc5i6WeHQpU5sRRMCynSmxrYA9kHEpBmMS2sVSLedL7';

    const POST = 0;
    const GET = 1;

    private $body = [];
    private $isAccepted = true;

    public function __construct($method = self::POST, $requires = []) {
        if ($method == self::POST) {
            $this->body = $_POST;
        } else if ($method == self::GET) {
            $this->body = $_GET;
        }
        foreach ($requires as $require) {
            if (!isset($this->body[$require])) {
                $this->isAccepted = false;
            }
        }
    }

    public function body($name, $default = null) {
        if (isset($this->body[$name])) {
            return $this->body[$name];
        }
        return $default;
    }

    public function json($body) {
        header('Content-Type: application/json');
        die(json_encode($body));
    }

    public function success($response = []) {
        $this->json(['status' => 'success', 'response' => $response]);
    }

    public function error($title, $message, $code = null) {
        if($code !== null) {
            http_response_code($code);
        }
        $this->json(['status' => 'error', 'error' => ['title' => $title, 'message' => $message]]);
    }

    public function isAccepted() {
        return $this->isAccepted;
    }
}
