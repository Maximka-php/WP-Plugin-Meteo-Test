
window.onload = function (){
    disabledInput();
    let geoIp = document.querySelector('#ip_geo');
    geoIp.addEventListener('click',function(){
        disabledInput();
    });
    function disabledInput(){
        let ipLat = document.querySelector('#ip_lat');
        let ipLong = document.querySelector('#ip_lon');
        let geoIp = document.querySelector('#ip_geo');
        if (geoIp.checked){
            ipLong.disabled = true;
            ipLat.disabled = true;
        }else {
            ipLong.disabled = false;
            ipLat.disabled = false;
        }
    }
};
