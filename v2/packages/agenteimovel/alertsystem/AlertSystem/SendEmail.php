<?php

class SendEmail {
    private $sparkpost;
    private $isTransactional;
    private $debug = false;
    private $apiKey = false;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    private function sparkpost($method, $uri, $payload = [], $headers = [])
    {
        $defaultHeaders = [ 'Content-Type: application/json' ];

        $curl = curl_init();
        $method = strtoupper($method);

        $finalHeaders = array_merge($defaultHeaders, $headers);

        $url = 'https://api.sparkpost.com:443/api/v1/'.$uri;

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if ($method !== 'GET') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $finalHeaders);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    public function setDebug($debug)
    {
        $this->debug = (bool)$debug;
    }

    private function isProductionEnvironment()
    {
        return true;
    }

    public function sendByTemplate($templateId, $substitutions, $recipients, $isTransactional, $isAbTest = false)
    {
        $settings = [
            'content' => [
            ],
            'substitution_data' => $substitutions,
            'recipients' => $recipients,
            'options' => [
                'transactional' => $isTransactional,
                'open_tracking' => true,
                'click_tracking' => true,            
            ]
        ];
        if(!$isAbTest){
            $settings['content']['template_id'] = $templateId;
            $settings['content']['use_draft_template'] = false;
        } else {
            $settings['content']['ab_test_id'] = $templateId;
        }
        return $this->send($settings);
    }

    public function sendByCustomContent($fromName, $fromEmail, $subject, $messageHtml, $messageText, $substitutions, $recipients, $isTransactional)
    {
        $settings = [
            'content' => [
                'from' => [
                    'name' => $fromName,
                    'email' => $fromEmail,
                ],
                'subject' => $subject,
                'html' => $messageHtml,
                'text' => $messageText,
            ],
            'substitution_data' => $substitutions,
            'recipients' => [
                $recipients
            ],
            'options' => [
                'transactional' => $isTransactional,
            ]
        ];
        return $this->send($settings);
    }

    private function getIpPool()
    {
        // if($this->isProductionEnvironment()){
        //     return 'dedicated';
        // }
        return '';
    }

    private function send($settings)
    {
        $ipPool = $this->getIpPool();
        if(!empty($ipPool)){
            $settings['options']['ip_pool'] = $ipPool;
        }
        if($this->debug){
            echo "\nDebug SparkPost\n";
            echo json_encode($settings);
            echo "\n";
        }
        
        $headers = [ 'Authorization: '.$this->apiKey ];
        $email_results = $this->sparkpost('POST', 'transmissions', $settings, $headers);

        if($this->debug){
            echo "\n".$email_results."\n";
        }
        return true;
    }

    public function sendTest($nameTo, $emailTo)
    {
        $settings = [
            'content' => [
                'from' => [
                    'name' => 'Sparkpost Inline Test',
                    'email' => 'from@alertsmail.agenteimovel.com.br',
                ],
                'subject' => 'Test Message',
                'html' => '<html><body><h1>Hello, {{name}}!</h1><p>The test email worked.</p></body></html>',
                'text' => 'Hello, {{name}}!! The test email worked.',
            ],
            'recipients' => [
                [
                    'address' => [
                        'name' => $nameTo,
                        'email' => $emailTo,
                    ],
                    'substitution_data' => [
                        'name' => $nameTo
                    ]
                ]
            ],
            'options' => [
                'transactional' => true,
            ]
        ];
        $result = $this->send($settings);
    }
}
?>