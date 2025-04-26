<?php

  if (isset($_POST['county']) && isset($_POST['constituency'])) {
    $county = $_POST['county'];
    $constituency = $_POST['constituency'];

    // Define delivery cost logic based on the county and constituency
    $delivery_costs = [
      'Nairobi' => [
        'Nairobi CBD' => 0,
        'Langata' => 80,
        'Dagoretti North' => 80,
        'Dagoretti South' => 80,
        'Westlands' => 80,
        'Makadara' => 80,
        'Embakasi North' => 80,
        'Embakasi East' => 80,
        'Embakasi West' => 80,
        'Embakasi South' => 80,
        'Embakasi Central' => 80,
        'Kamukunji' => 80,
        'Ruaraka' => 80,
        'Kasarani' => 80,
        'Roysambu' => 80,
        'Kibra' => 80,
        'Mathare' => 80,
        'Starehe' => 80,
        'Makadara' => 80
      ],
      'Baringo' => [
        'Kabarnet Town-> Easy Coach Bus Station' => 150
      ],
      'Bomet' => [
        'Bomet Town' => 150
      ],
      'Bungoma' => [
        'Bungoma Town' => 150,
        'Webuye' => 150
      ],
      'Busia' => [
        'Busia Town' => 150,
        'Malaba Town' => 150
      ],
      'Elgeyo Marakwet' => ['Iten' => 140],
      'Embu' => ['Embu Town' => 140],
      'Garissa' => ['Garissa Township' => 210],
      'Homa Bay' => ['Homa Bay Town' => 180],
      'Isiolo' => ['Isiolo Town' => 160],
      'Kajiado' => ['Kajiado Town' => 120],
      'Kakamega' => ['Kakamega Town' => 150],
      'Kericho' => ['Kericho Town' => 120],
      'Kiambu' => [
        'Gatundu Town' => 90,
        'Juja' => 90,
        'Limuru' => 90,
        'Muthaiga North' => 90,
        'Ruaka' => 90,
        'Thika Town' => 90,
        'Kenyatta RD/KU Boma' => 90,
        'WANGIGE' => 90,
        'Banana Hill' => 90
      ],
      'Kilifi' => [
        'Kilifi Town' => 140,
        'Malindi' => 140,
        'Watamu' => 140
      ],
      'Kirinyaga' => [
        'Kagio' => 105,
        'Keruguya Town' => 105,
        'Kutus' => 105
      ],
      'Kisii' => [
        'Kisii Town' => 140,
        'Keroka' => 140
      ],
      'Kisumu' => [
        'Kisumu CBD' => 140,
        'Maseno' => 140,
        'Kondele' => 140,
        'Ahero' => 140
      ],
      'Kitui' => [
        'Kitui' => 100,
        'Mwingi' => 100
      ],
      'Kwale' => [
        'Kwale Town' => 130,
        'LungaLunga' => 130
      ],
      'Laikipia' => [
        'Nyahururu' => 135,
        'Nanyuki Town' => 135
      ],
      'Lamu' => [
        'Lamu' => 130,
        'Mpeketoni' => 130
      ],
      'Machakos' => [
        'Athi River/Day Star University' => 100,
        'Joska' => 100,
        'Kangundo' => 100,
        'Machakos Town' => 100,
        'Syokimau/Mlolongo' => 100,
        'Tala' => 100
      ],
      'Makueni' => [
        'Kibwezi' => 105,
        'Wote' => 105,
        'Emali Town' => 105,
        'Mtito Andei' => 105
      ],
      'Marsabit' => ['Marsabit Town' => 190],
      'Meru' => [
        'Meru Town' => 125,
        'Makutano' => 125,
        'Maua' => 125
      ],
      'Migori' => [
        'Migori Town' => 165,
        'Awendo' => 165,
        'Rongo' => 165
      ],
      'Mombasa' => [
        'Bombolulu' => 130,
        'Likoni' => 130,
        'Nyali' => 130,
        'Mvita Tudor' => 130,
        'Shanzu' => 130
      ],
      'Muranga' => [
        'Muranga Town' => 115,
        'Kenol' => 115
      ],
      'Nakuru' => [
        'Nakuru CBD' => 95,
        'Gilgil' => 95,
        'Bahati' => 95,
        'Industrial Area/LangaLanga' => 95,
        'Naivasha' => 95
      ],
      'Nandi' => [
        'Nandi Hills' => 115,
        'Kapsabet Town' => 115
      ],
      'Narok' => ['Narok Town' => 115],
      'Nyamira' => ['Nyamira Town' => 135],
      'Nyandarua' => ['Ol Kalou' => 135],
      'Nyeri' => [
        'Nyeri Town' => 115,
        'Othaya' => 115,
        'Chaka' => 115,
        'Karatina/Konyu' => 115],
      'Samburu' => ['Maralal' => 150],
      'Siaya' => [
        'Siaya Town' => 160,
        'Bondo' => 160,
        'Ugunja' => 160],
      'Taita Taveta' => [
        'Taita' => 125,
        'Voi' => 125,
        'Mwatate' => 125
      ],
      'Tana River' => [
        'Bura' => 125,
        'Hola' => 125
      ],
      'Tharaka Nithi' => [
        'Chuka Town' => 125,
        'Chogoria' => 125
      ],
      'Trans Nzoia' => [
        'Kitale' => 125,
        "Moi's Bridge" => 125
      ],
      'Turkana' => ['Lodwar' => 170],
      'Uasin Gishu' => [
        'Eldoet Town' => 120,
        'Hawaii Munyaka' => 120,
        'KCC/Ilula' => 120,
        'Nairobi RD' => 120,
        'Uganda RD' => 120
      ],
      'Vihiga' => [
        'Majengo' => 155,
        'Mbale' => 155,
        'Luanda' => 155
      ],
      'Wajir' => ['Wajir' => 180],
      'West Pokot' => ['Kapenguria' => 170]
      // Add more counties and constituencies with delivery costs
    ];

    // Check if the selected county and constituency exist
    if (isset($delivery_costs[$county][$constituency])) {
        echo $delivery_costs[$county][$constituency];
    } else {
        echo '0'; // Default cost or error handling
    }
  }


 ?>
