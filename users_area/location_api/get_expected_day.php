<?php

  if (isset($_POST['county']) && isset($_POST['constituency'])) {
    $county = $_POST['county'];
    $constituency = $_POST['constituency'];

    // Define delivery cost logic based on the county and constituency
    $expected_day = [
      'Nairobi' => [
        'Nairobi CBD' => 4,
        'Langata' => 4,
        'Dagoretti North' => 4,
        'Dagoretti South' => 4,
        'Westlands' => 4,
        'Makadara' => 4,
        'Embakasi North' => 4,
        'Embakasi East' => 4,
        'Embakasi West' => 4,
        'Embakasi South' => 4,
        'Embakasi Central' => 4,
        'Kamukunji' => 4,
        'Ruaraka' => 4,
        'Kasarani' => 4,
        'Roysambu' => 4,
        'Kibra' => 4,
        'Mathare' => 4,
        'Starehe' => 4,
        'Makadara' => 4
      ],
      'Baringo' => [
        'Kabarnet Town-> Easy Coach Bus Station' => 8
      ],
      'Bomet' => [
        'Bomet Town' => 8
      ],
      'Bungoma' => [
        'Bungoma Town' => 8,
        'Webuye' => 8
      ],
      'Busia' => [
        'Busia Town' => 8,
        'Malaba Town' => 8
      ],
      'Elgeyo Marakwet' => ['Iten' => 7],
      'Embu' => ['Embu Town' => 5],
      'Garissa' => ['Garissa Township' => 14],
      'Homa Bay' => ['Homa Bay Town' => 10],
      'Isiolo' => ['Isiolo Town' => 9],
      'Kajiado' => ['Kajiado Town' => 9],
      'Kakamega' => ['Kakamega Town' => 7],
      'Kericho' => ['Kericho Town' => 6],
      'Kiambu' => [
        'Gatundu Town' => 4,
        'Juja' => 4,
        'Limuru' => 4,
        'Muthaiga North' => 4,
        'Ruaka' => 4,
        'Thika Town' => 4,
        'Kenyatta RD/KU Boma' => 4,
        'WANGIGE' => 4,
        'Banana Hill' => 90
      ],
      'Kilifi' => [
        'Kilifi Town' => 6,
        'Malindi' => 6,
        'Watamu' => 6
      ],
      'Kirinyaga' => [
        'Kagio' => 6,
        'Keruguya Town' => 6,
        'Kutus' => 6
      ],
      'Kisii' => [
        'Kisii Town' => 7,
        'Keroka' => 7
      ],
      'Kisumu' => [
        'Kisumu CBD' => 7,
        'Maseno' => 7,
        'Kondele' => 7,
        'Ahero' => 7
      ],
      'Kitui' => [
        'Kitui' => 5,
        'Mwingi' => 5
      ],
      'Kwale' => [
        'Kwale Town' => 6,
        'LungaLunga' => 6
      ],
      'Laikipia' => [
        'Nyahururu' => 6,
        'Nanyuki Town' => 6
      ],
      'Lamu' => [
        'Lamu' => 6,
        'Mpeketoni' => 6
      ],
      'Machakos' => [
        'Athi River/Day Star University' => 5,
        'Joska' => 5,
        'Kangundo' => 5,
        'Machakos Town' => 5,
        'Syokimau/Mlolongo' => 5,
        'Tala' => 5
      ],
      'Makueni' => [
        'Kibwezi' => 5,
        'Wote' => 5,
        'Emali Town' => 5,
        'Mtito Andei' => 5
      ],
      'Marsabit' => ['Marsabit Town' => 12],
      'Meru' => [
        'Meru Town' => 6,
        'Makutano' => 6,
        'Maua' => 6
      ],
      'Migori' => [
        'Migori Town' => 8,
        'Awendo' => 8,
        'Rongo' => 8
      ],
      'Mombasa' => [
        'Bombolulu' => 6,
        'Likoni' => 6,
        'Nyali' => 6,
        'Mvita Tudor' => 6,
        'Shanzu' => 6
      ],
      'Muranga' => [
        'Muranga Town' => 5,
        'Kenol' => 5
      ],
      'Nakuru' => [
        'Nakuru CBD' => 5,
        'Gilgil' => 5,
        'Bahati' => 5,
        'Industrial Area/LangaLanga' => 5,
        'Naivasha' => 5
      ],
      'Nandi' => [
        'Nandi Hills' => 8,
        'Kapsabet Town' => 8
      ],
      'Narok' => ['Narok Town' => 8],
      'Nyamira' => ['Nyamira Town' => 7],
      'Nyandarua' => ['Ol Kalou' => 7],
      'Nyeri' => [
        'Nyeri Town' => 7,
        'Othaya' => 7,
        'Chaka' => 7,
        'Karatina/Konyu' => 7],
      'Samburu' => ['Maralal' => 140],
      'Siaya' => [
        'Siaya Town' => 8,
        'Bondo' => 8,
        'Ugunja' => 8],
      'Taita Taveta' => [
        'Taita' => 6,
        'Voi' => 6,
        'Mwatate' => 6
      ],
      'Tana River' => [
        'Bura' => 5,
        'Hola' => 5
      ],
      'Tharaka Nithi' => [
        'Chuka Town' => 6,
        'Chogoria' => 6
      ],
      'Trans Nzoia' => [
        'Kitale' => 6,
        "Moi's Bridge" => 6
      ],
      'Turkana' => ['Lodwar' => 12],
      'Uasin Gishu' => [
        'Eldoet Town' => 9,
        'Hawaii Munyaka' => 9,
        'KCC/Ilula' => 9,
        'Nairobi RD' => 9,
        'Uganda RD' => 9
      ],
      'Vihiga' => [
        'Majengo' => 8,
        'Mbale' => 8,
        'Luanda' => 8
      ],
      'Wajir' => ['Wajir' => 10],
      'West Pokot' => ['Kapenguria' => 9]
      // Add more counties and constituencies with delivery costs
    ];

    // Check if the selected county and constituency exist
    if (isset($expected_day[$county][$constituency])) {
        echo $expected_day[$county][$constituency];
    } else {
        echo '0'; // Default cost or error handling
    }
  }


 ?>
