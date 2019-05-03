<html>
<head>
<meta charset="gb2312">
<title>游久网问答让你一目了然-游久网</title>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0 ,user-scalable=no">
<meta name="baidu-site-verification" content="jwogHc4G7G" />
<link type="text/css" rel="stylesheet" href="/skin20170713/css/style.css"/>
<link href="http://dev.uuu9.com/MobileTuJi/gallery.css" rel="stylesheet" />
<script type="text/javascript" src="http://dev.uuu9.com/js/jQuery/jquery-1.8.3.min.js"></script>
<script src="http://dev.uuu9.com/MobileTuJi/MobileTuJi.js?v=20151223104647"></script>
<script src="http://dev.uuu9.com/js/tool/autoloadpage.js?t=20151223104647"></script>
<!--微信分享代码-->
<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript" src="http://event.uuu9.com/Public/js/wx_share.js"></script>
<script type="text/javascript">
var deviceWidth = document.documentElement.clientWidth;
if(deviceWidth > 750) deviceWidth = 750;
document.documentElement.style.fontSize = deviceWidth / 7.5 + 'px';
alp.init("textdetail", "css_page_box");
jQuery(function(){ 
jQuery(".sort").click(
function(){
jQuery(".dnav").toggle();
})
});
</script>
<style type="text/css">
  .box-page {
    margin-top: 20px;
    text-align: center;
  }
  .box-page > .page {
    display: inline-block;
    width: 40px;
    height: 40px;
    background-color: #ff9434;
    color: #FFF;
    text-align: center;
    line-height: 40px;
    transition: 0.3s;
    border-radius: 3px;
    margin: 0px 5px;
  }
  .box-page > .page:hover {
    background-color: #ffb464;
    cursor: pointer;
  }
  .box-page > .page.disabled {
    background-color: #ffb464;
  }
</style>
</head>
<body>
  <div class="all">
  <div class="tophead cl">
            <h1 class="logo"><a href="http://m.chiji.uuu9.com"><img src="http://image.uuu9.com/pcgame/pubg/UploadFiles/201709/201709301734348431.png" /></a></h1>
       <a class="sort r">导航</a>
  </div>
      <div class="dnav" style="display:none;">
        <ul>
                <li><span><a href="http://m.chiji.uuu9.com/" >首页</a></span></li>
            <li><span><a href="http://m.chiji.uuu9.com/sp/jxsp/">教学</a></span></li>
            <li><span><a href="http://m.chiji.uuu9.com/sp/jcjj/" >集锦</a></span></li>
            <li><span><a href="http://m.chiji.uuu9.com/sp/cfsp/" >爆笑</a></span></li>
            <li><span><a href="http://m.chiji.uuu9.com/xw/" >快报</a></span></li>
            <li><span><a href="http://m.chiji.uuu9.com/xw/yxgl/" >攻略</a></span></li>
        </ul>
    </div>
    <div class="newsbox cl">
        <div class="title"><h2>最新文章</h2></div>
        <div class="newscon">     
          @foreach ($faqs as $faq)
              <a href="{{$faq->getLink()}}">
                <dl class="cl">
                    <dt><img src="{{$faq->image}}" alt="{{$faq->title}}"></dt>
                    <dd>
                        <h4>{{$faq->title}}</h4>
                        <div class="cl m-10">
                            <span class="   gl xl"></span>
                            <span class="time r">09/10</span>
                     </div> 
                    </dd>
                </dl>
                </a>
          @endforeach   
        </div>
        <div class='box-page'>
        </div>
    </div>
  <div class="footer m-15">
    
      <div class="copyright">
          <p><a href="http://moba.uuu9.com/thread-4783200-1-1.html">我要投稿</a><a href="http://www.uuu9.com/about/contact/contact01.shtml">联系我们</a><a href="http://www.uuu9.com/about/index.shtml">关于我们</a><a href="http://www.uuu9.com/about/hr/hr03.shtml">加入我们</a><a href="http://www.uuu9.com/feedback/UsersFeedback.aspx">意见反馈</a></p>
      </div>
      <div class="footer_logo"><img src="http://chiji.uuu9.com/skin20170713/images/footer_logo.png"></div>
  </div>
</div> 



 <div style="display:none;">
<script src="https://s5.cnzz.com/z_stat.php?id=4990579&web_id=4990579" language="JavaScript"></script>
<script src="https://s22.cnzz.com/z_stat.php?id=4995947&web_id=4995947" language="JavaScript"></script>
<script src="https://s13.cnzz.com/z_stat.php?id=1263909696&web_id=1263909696" language="JavaScript"></script>
</div>
</body>

<script type="text/javascript">
$(function(){
  setPage();

  $('.box-page .page').click(function(){
    if($(this).hasClass('disabled')){
      return false
    }

    var page = $(this).html()
    var nPage = getUrlParam('page')
    if(!nPage) nPage = 1
    nPage = nPage < 1 ? 1 : parseInt(nPage)
    
    if(page == '&lt;'){
      page = nPage - 1
    }
    if(page == '&gt;'){
      page = nPage + 1
    }
    var url = setUrlParam('page', page)
    window.open(url, '_self')
  })
})

function setPage(){

  var now = {{$page_info['now']}}
  var max = {{$page_info['max']}}
  var maxShow = 7 //最大展示个数
  var pageHtml = []

  if(max == 1){
    return true
  }

  //上一页添加
  if(now == 1){
    pageHtml.push('<div class="page disabled">&lt;</div>')
  }else{
    pageHtml.push('<div class="page">&lt;</div>')
  }

  var start = (now - Math.floor(maxShow/2)) <= 1 ? 1 : (now - Math.floor(maxShow/2))
  for(i = start; i < start + maxShow; i++){
    if(i > max){
      break;
    }
    if(i == now){
      pageHtml.push('<div class="page disabled">'+i+'</div>')
    }else{
      pageHtml.push('<div class="page">'+i+'</div>')
    }
    
  }

  //下一页添加
  if(now == max){
    pageHtml.push('<div class="page disabled">&gt;</div>')
  }else{
    pageHtml.push('<div class="page">&gt;</div>')
  }

  for(var k in pageHtml){
    var page = pageHtml[k]
    $('.box-page').append(page)
  }
}

function setUrlParam(key, value) {
    var url = window.location.href
    var anchor = "";
    var anchor_index = url.indexOf("#");
    if (anchor_index >= 0) {
        anchor = url.substr(anchor_index);
        url = url.substr(0, anchor_index);
    }
    key = encodeURIComponent(key);
    var patt;
    patt = new RegExp("\\&" + key + "=[^&]*", "gi");
    url = url.replace(patt, "");
    patt = new RegExp("\\?" + key + "=[^&]*\\&", "gi");
    url = url.replace(patt, "?");
    patt = new RegExp("\\?" + key + "=[^&]*$", "gi");
    url = url.replace(patt, "");
    if (value != undefined && value != null) {
        value = encodeURIComponent(value);
        url += (url.indexOf("?") >= 0 ? "&" : "?") + key + "=" + value;
    }
    url += anchor;
    return url;
}

function getUrlParam(key) {
    var url = window.location.href
    key = encodeURIComponent(key);
    var patt = new RegExp("[?&]" + key + "=([^&#]*)", "gi");
    var values = [];
    while ((result = patt.exec(url)) != null) {
        values.push(decodeURIComponent(result[1]));
    }
    if (values.length > 0) {
        return values.join(",");
    } else {
        return null;
    }
}
</script>
</html>
                                        
