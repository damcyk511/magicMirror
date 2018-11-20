
//https://chrome.google.com/webstore/detail/allow-control-allow-origi/nlfbmbojpeacfghkpbjhddihlkkiljbi/related?hl=en-US
var xmlHttp = new XMLHttpRequest();
xmlHttp.open("GET", 'https://psnprofiles.com/damcyk511', false); 
xmlHttp.send(null);

var psnprofilesData = xmlHttp.responseText;
console.log(psnprofilesData);

var level         = psnprofilesData.substring(219, 221);
var worldRank     = psnprofilesData.substring(262, 269).replace(',','.');
var countryRank   = psnprofilesData.substring(286, 292).replace(',','.');
var totalTrophies = psnprofilesData.substring(15895, 15899);
console.log(psnprofilesData);
console.log(level);
console.log(worldRank);
console.log(countryRank);
console.log(totalTrophies);