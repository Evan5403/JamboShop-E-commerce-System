<?php

$counties = [
  'Baringo' => ['Kabarnet Town'],
  'Bomet' => ['Bomet Town'],
  'Bungoma' => ['Bungoma Town','Webuye'],
  'Busia' => ['Busia Town','Malaba Town'],
  'Elgeyo Marakwet' => ['Iten'],
  'Embu' => ['Embu Town'],
  'Garissa' => ['Garissa Township'],
  'Homa Bay' => ['Homa Bay Town'],
  'Isiolo' => ['Isiolo Town'],
  'Kajiado' => ['Kajiado Town'],
  'Kakamega' => ['Kakamega Town'],
  'Kericho' => ['Kericho Town'],
  'Kiambu' => ['Gatundu Town','Juja','Limuru','Muthaiga North','Ruaka','Thika Town','Kenyatta RD/KU Boma','WANGIGE','Banana Hill'],
  'Kilifi' => ['Kilifi Town','Malindi','Watamu'],
  'Kirinyaga' => ['Kagio','Keruguya Town','Kutus'],
  'Kisii' => ['Kisii Town','Keroka'],
  'Kisumu' => ['Kisumu CBD','Maseno','Kondele','Ahero'],
  'Kitui' => ['Kitui','Mwingi'],
  'Kwale' => ['Kwale Town','LungaLunga'],
  'Laikipia' => ['Nyahururu','Nanyuki Town'],
  'Lamu' => ['Lamu','Mpeketoni'],
  'Machakos' => ['Athi River/Day Star University','Joska','Kangundo','Machakos Town','Syokimau/Mlolongo','Tala'],
  'Makueni' => ['Kibwezi','Wote','Emali Town','Mtito Andei'],
  'Marsabit' => ['Marsabit Town'],
  'Meru' => ['Meru Town','Makutano','Maua'],
  'Migori' => ['Migori Town','Awendo','Rongo'],
  'Mombasa' => ['Bombolulu','Likoni','Nyali','Mvita Tudor','Shanzu'],
  'Muranga' => ['Muranga Town','Kenol'],
  'Nairobi' => ['Nairobi CBD','Langata','Dagoretti North','Dagoretti South','Westlands','Makadara','Embakasi North','Embakasi East',
                'Embakasi West','Embakasi South','Embakasi Central','Kamukunji','Ruaraka','Kasarani','Roysambu','Kibra','Mathare','Starehe',
                'Makadara'],
  'Nakuru' => ['Nakuru CBD','Gilgil','Bahati','Industrial Area/LangaLanga','Naivasha'],
  'Nandi' => ['Nandi Hills','Kapsabet Town'],
  'Narok' => ['Narok Town'],
  'Nyamira' => ['Nyamira Town'],
  'Nyandarua' => ['Ol Kalou'],
  'Nyeri' => ['Nyeri Town','Othaya','Chaka','Karatina/Konyu'],
  'Samburu' => ['Maralal'],
  'Siaya' => ['Siaya Town','Bondo','Ugunja'],
  'Taita Taveta' => ['Taita','Voi','Mwatate'],
  'Tana River' => ['Bura','Hola'],
  'Tharaka Nithi' => ['Chuka Town','Chogoria'],
  'Trans Nzoia' => ['Kitale',"Moi's Bridge"],
  'Turkana' => ['Lodwar'],
  'Uasin Gishu' => ['Eldoet Town','Hawaii Munyaka','KCC/Ilula','Nairobi RD','Uganda RD'],
  'Vihiga' => ['Majengo','Mbale','Luanda'],
  'Wajir' => ['Wajir'],
  'West Pokot' => ['Kapenguria']
];


  foreach (array_keys($counties) as $county) {
    echo '<option value="'.$county.'">'.$county.'</option>';
  }

 ?>
