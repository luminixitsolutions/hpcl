<nav class="layout-footer footer footer-light d-block d-md-none">
<div class="container-fluid d-flex flex-wrap justify-content-between text-center container-p-x pb-3">
<div class="pt-3">
<span class="float-md-right d-none d-lg-block">&copy; <?php echo $Proj_Title; ?> <i class="fas fa-heart text-danger mr-2"></i></span>
</div>
<!-- <div>
<a href="javascript:" class="footer-link pt-3">About Us</a>
<a href="javascript:" class="footer-link pt-3 ml-4">Help</a>
<a href="javascript:" class="footer-link pt-3 ml-4">Contact</a>
<a href="javascript:" class="footer-link pt-3 ml-4">Terms &amp; Conditions</a>
</div> -->
</div>
</nav>
<script>
(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var userId = urlParams.get('user_id') || '';
    var lat = urlParams.get('lat') || '';
    var lng = urlParams.get('lng') || '';
    
    function addParams(url) {
        if (!url || (url.indexOf('hpclpos.com') === -1 && url.indexOf('kwickfoods.in') === -1)) return url;
        var urlObj = new URL(url, window.location.origin);
        urlObj.searchParams.set('user_id', userId);
        urlObj.searchParams.set('lat', lat);
        urlObj.searchParams.set('lng', lng);
        return urlObj.toString();
    }
    
    document.addEventListener('click', function(e) {
        var target = e.target;
        while (target && target.tagName !== 'A') target = target.parentElement;
        if (target && target.href) {
            var newHref = addParams(target.href);
            if (newHref !== target.href) {
                e.preventDefault();
                window.location.href = newHref;
            }
        }
    }, true);
})();
</script>
<script>
  const APP_PARAMS = {
    user_id: "<?php echo htmlspecialchars($displayUserId); ?>",
    lat: "<?php echo htmlspecialchars($lat); ?>",
    lng: "<?php echo htmlspecialchars($lng); ?>"
  };

  function goPage(url) {
    const params = new URLSearchParams(APP_PARAMS).toString();
    window.location.href = url.includes('?')
      ? url + '&' + params
      : url + '?' + params;
  }
</script>
<style>


.navbar11 {
  overflow: hidden;
  background-color: #fff;
  position: fixed;
  bottom: 0;
  width: 100%;
  border-radius: 20px 20px 0 0;
  box-shadow: 0 -3px 7px rgba(0, 0, 0, 0.15);
-webkit-box-shadow: 0 -3px 7px rgba(0, 0, 0, 0.15);
-moz-box-shadow: 0 -3px 7px rgba(0, 0, 0, 0.15);
-ms-box-shadow: 0 -3px 7px rgba(0, 0, 0, 0.15);
z-index: 999;
}

.navbar11 a {
  float: left;
  display: block;
  color: #000;
  text-align: center;
  padding: 10px 16px;
  text-decoration: none;
  font-size: 14px;
}

.navbar11 a:hover {
  background: #f1f1f1;
  color: black;
}



.main11 {
  padding: 16px;
}
</style>

<!--<div class="navbar11 d-block d-md-none">

    <div class="footer">
        <div class="row no-gutters justify-content-center">
            <div class="col-auto">
                <a href="dashboard.php"  class="active" >
                    <i style="font-size:20px;" class="lnr lnr-home"></i><br>
                    <span>Home</span>
                </a>
            </div>
           
       
            
            
            <div class="col-auto">
                <a href="cart.php" >
                     <i style="font-size:20px;"  class="lnr lnr-cart"></i><br>
                    <span>Cart <span class="badge" style="background-color: bisque;" id="cnt">0</span></span>
                </a>
            </div>
            
            
            
        </div>
    </div>
</div>-->


</body>
</html>
