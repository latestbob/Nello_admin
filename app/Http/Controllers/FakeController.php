$object = $data['object'];
        $whatsbussiness_id = $data['entry'][0]['id']; //business whatsapp id
       $phone_number_id = $data['entry'][0]['changes'][0]['value']['metadata']['phone_number_id'];
        $display_phone_number = $data['entry'][0]['changes'][0]['value']['metadata']['display_phone_number']; //bot number display number

        // Access the "profile" name
        $profileName = $data['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];

        // Access the "wa_id for recipient phone number"
        $waId = $data['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'];

        // Access the "text" body
       $textBody = $data['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];

       $token = 'EAAP8un1x1BEBO98ljT2ZBMZBJ0FAeqxKcTrpb0BhUzP9N3ymhLZAzNWNvuTYyugwhV53CZAJ1AjRpxPpYxUObQhtVDEyNlf1IQDbzdzyQ96a20bjhuuJRg3EsHJELk9rqaZBkpaZCEmnIZB4ZCZCynBG0GYZBZBkLZBDpuPiGmnuVqPYhgzEZB5mNhdZCE8MhPraHBhTJclKm6Yp7mFFA74IOS83xc';


       if($textBody === "Hi" || $textBody === 'Hello' || $textBody === 'Start' || $textBody === "Get Started"){

//////////////post send meessate template

$responsed = Http::withoutVerifying()->withHeaders([
    'Authorization'=> "Bearer ". $token,
    
])->post('https://graph.facebook.com/v19.0/101097306144140/messages',[
    "messaging_product" => "whatsapp",
    "to" => $waId,
    "type" => "template",
    
    "template" => [
        'name' => 'nello_welcome',
        'language' => [
            'code' => 'en_US'
        ],

    ],
    "parameter1" => "next_step",
   
]);





}