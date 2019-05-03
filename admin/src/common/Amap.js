//高德地图api
export default {

    //坐标拾取器
    setMarkerPosition : function(domId, origin, callback){
        var map     = new AMap.Map(domId, {
            resizeEnable: true,
            center: origin,
            zoom: 13
        });

        var marker = new AMap.Marker({ //添加自定义点标记
            map: map,
            position: origin, //基点位置
            offset: new AMap.Pixel(-17, -42), //相对于基点的偏移位置
            draggable: true,  //是否可拖动
        });

        AMap.event.addListener(marker, 'dragend', function(){
            var position    = marker.getPosition();
            callback(position.lng, position.lat);
        })
        
        // marker.setPosition([]);

        var clickEventListener = map.on('click', function(e) {
            marker.setPosition([e.lnglat.getLng(), e.lnglat.getLat()]);
            callback(e.lnglat.getLng(), e.lnglat.getLat());
        });

        callback(origin[0], origin[1]);
    }
}