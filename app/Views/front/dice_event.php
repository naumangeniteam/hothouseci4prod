<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
var settings = {
  "url": "https://dice.fm/_next/data/mdyfxrMsF3MgmhvK1m36H/browse/new-york/music/gig/jazz.json",
  "method": "GET",
  "timeout": 0,
  "headers": {
    "Access-Control-Allow-Origin": "*",
    "Cookie": "__cf_bm=EXIM0CFH_szaRd_dR6cmCfCqy9xqMlmOyVu2jn8y_D4-1734600226-1.0.1.1-AHikUpXk4mlsXXjtQRr4dkTsstK1CfYXOr6UIiiacOUZ0osoN3LThLuysX9gCcQUP7gWT8lgLXmnd1x3ppdxmA"
  },
};

$.ajax(settings).done(function (response) {
  alert(response.pageProps);
});
</script>