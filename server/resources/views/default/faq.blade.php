<html>
  <head>
    <meta charset="gb2312">
    <title>{{$faq->title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 ,user-scalable=no">
    <meta name="baidu-site-verification" content="jwogHc4G7G" />
    <link type="text/css" rel="stylesheet" href="/skin20170713/css/style.css"/>
    <link href="http://dev.uuu9.com/MobileTuJi/gallery.css" rel="stylesheet"/>
    <script type="application/ld+json">
      {
        "@context": "https://ziyuan.baidu.com/contexts/cambrian.jsonld",
        "@id": "{{$faq->getLink()}}",
        "appid": "1595441886555530",
        "title": "{{$faq->title}}",
        "images": [
          "{{$faq->image}}"
        ],
        "description": "{{$faq->description}}",
        "pubDate": "<?php echo date('Y-m-d', strtotime($faq->created_at)).'T'.date('H:i:s', strtotime($faq->created_at)); ?>",
        "upDate": "<?php echo date('Y-m-d', strtotime($faq->created_at)).'T'.date('H:i:s', strtotime($faq->created_at)); ?>",
        "lrDate": "<?php echo date('Y-m-d', strtotime($faq->created_at)).'T'.date('H:i:s', strtotime($faq->created_at)); ?>",
        "data": {
          "WebPage": {
            "headline": "{{$faq->headline}}",
            "mipUrl": "http://mip.example.com/path/page.html",
            "fromSrc": "站点名称",
            "domain": "游戏",
            "category": [
              "问答"
            ],
            "isDeleted": 0
          },
          "Question": [{
            "acceptedAnswer": "{{$faq->description}}"
          }],
          "ImageObject": [{
            "contentUrl": "{{$faq->image}}",
            "scale": "5:2"
          }],
          "Author": [{
            "name": "{{$faq->author}}",
            "memberOf": {
              "legalName": "机构名称",
              "department": "部门名称",
              "organizationLevel": "级别"
            },
            "jobTitle": [
              "作家"
            ],
            "headPortrait": "http://www.example.com/producer/avatar.jpg"
          }],
          "VideoObject": [{
            "contentUrl": "http://www.example.com/12345.mp4",
            "duration": 557,
            "scale": "16:9",
            "thumbnail": {
              "contentUrl": "http://www.example.com/poster.jpg",
              "scale": "16:9"
            }
          }],
          "AudioObject": [{
            "contentUrl": "http://www.example.com/a.mp3",
            "duration": 557
          }]
        }
      }
    </script>
    <script type="text/javascript" src="http://dev.uuu9.com/js/jQuery/jquery-1.8.3.min.js">
    </script>
    <script src="http://dev.uuu9.com/MobileTuJi/MobileTuJi.js?v=20151223104647">
    </script>
    <script src="http://dev.uuu9.com/js/tool/autoloadpage.js?t=20151223104647">
    </script>
    <!--微信分享代码-->
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js">
    </script>
    <script type="text/javascript" src="http://event.uuu9.com/Public/js/wx_share.js">
    </script>
    <script type="text/javascript">
      var deviceWidth = document.documentElement.clientWidth;
      if (deviceWidth > 750) deviceWidth = 750;
      document.documentElement.style.fontSize = deviceWidth / 7.5 + 'px';
      alp.init("textdetail", "css_page_box");
      jQuery(function() {
          jQuery(".sort").click(function() {
              jQuery(".dnav").toggle();
          })
      });
    </script>
  </head>
  
  <body>
    <div class="all">
      <div class="tophead cl">
        <h1 class="logo">
          <a href="http://m.chiji.uuu9.com">
            <img src="http://image.uuu9.com/pcgame/pubg/UploadFiles/201709/201709301734348431.png"/>
          </a>
        </h1>
        <a class="sort r">导航</a>
      </div>
      <div class="dnav" style="display:none;">
        <ul>
          <li><span><a href="http://m.chiji.uuu9.com/">首页</a></span></li>
          <li><span><a href="http://m.chiji.uuu9.com/sp/jxsp/">教学</a></span></li>
          <li><span><a href="http://m.chiji.uuu9.com/sp/jcjj/">集锦</a></span></li>
          <li><span><a href="http://m.chiji.uuu9.com/sp/cfsp/">爆笑</a></span></li>
          <li><span><a href="http://m.chiji.uuu9.com/xw/">快报</a></span></li>
          <li><span><a href="http://m.chiji.uuu9.com/xw/yxgl/">攻略</a></span></li>
        </ul>
      </div>
      <div class="topimg">
        <div class="mask">
        </div>
        <img src="http://image.uuu9.com/pcgame/pubg/UploadFiles/201709/201709061124458751.jpg" />
        <div class="titleWrap">
          <h1>{{$faq->title}}</h1>
          <i>时间：{{$faq->created_at}}</i>
          <span>作者：{{$faq->author}}</span>
        </div>
      </div>
      <style>
        iframe { width:100%; height:200px;} embed { width:100%; height:200px;}
      </style>
      <div class="detail">
        <div class="textdetail">
          <?php echo $faq->answer; ?>
        </div>
      </div>
      <div class="newsbox cl">
        <div class="title">
          <h2>最新文章</h2>
        </div>
        <div class="newscon">
          <a href="/xw/ssxw/201809/577029.shtml">
            <dl class="cl">
              <dt>
                <img src="http://image.uuu9.com/pcgame/pubg/UploadFiles/201809/201809101737089681.jpg"
                alt="Imba超级联赛参赛队伍名单正式公布">
              </dt>
              <dd>
                <h4>
                  Imba超级联赛参赛队伍名单正式公布
                </h4>
                <div class="cl m-10">
                  <span class="   gl xl">
                  </span>
                  <span class="time r">
                    09/10
                  </span>
                </div>
              </dd>
            </dl>
          </a>
          <a href="/xw/zxxw/201809/577025.shtml">
            <dl class="cl">
              <dt>
                <img src="http://image.uuu9.com/pcgame/pubg/UploadFiles/201809/201809101714231871.jpg"
                alt="PIT深渊联赛RNG出线 将于Faze、C9同台竞技">
              </dt>
              <dd>
                <h4>
                  PIT深渊联赛RNG出线 将于Faze、C9同台竞技
                </h4>
                <div class="cl m-10">
                  <span class="   gl xl">
                  </span>
                  <span class="time r">
                    09/10
                  </span>
                </div>
              </dd>
            </dl>
          </a>
          <a href="/xw/zxxw/201809/577023.shtml">
            <dl class="cl">
              <dt>
                <img src="http://image.uuu9.com/pcgame/pubg/UploadFiles/201809/201809101651310151.jpg"
                alt="国外解说证实雪地传言：大小6*6 11月开测">
              </dt>
              <dd>
                <h4>
                  国外解说证实雪地传言：大小6*6 11月开测
                </h4>
                <div class="cl m-10">
                  <span class="   gl xl">
                  </span>
                  <span class="time r">
                    09/10
                  </span>
                </div>
              </dd>
            </dl>
          </a>
        </div>
      </div>
      <div class="footer m-15">
        <div class="copyright">
          <p>
            <a href="http://moba.uuu9.com/thread-4783200-1-1.html">
              我要投稿
            </a>
            <a href="http://www.uuu9.com/about/contact/contact01.shtml">
              联系我们
            </a>
            <a href="http://www.uuu9.com/about/index.shtml">
              关于我们
            </a>
            <a href="http://www.uuu9.com/about/hr/hr03.shtml">
              加入我们
            </a>
            <a href="http://www.uuu9.com/feedback/UsersFeedback.aspx">
              意见反馈
            </a>
          </p>
        </div>
        <div class="footer_logo">
          <img src="http://chiji.uuu9.com/skin20170713/images/footer_logo.png">
        </div>
      </div>
    </div>
    <div style="display:none;">
      <script src="https://s5.cnzz.com/z_stat.php?id=4990579&web_id=4990579" language="JavaScript">
      </script>
      <script src="https://s22.cnzz.com/z_stat.php?id=4995947&web_id=4995947" language="JavaScript">
      </script>
      <script src="https://s13.cnzz.com/z_stat.php?id=1263909696&web_id=1263909696" language="JavaScript">
      </script>
    </div>
  </body>
</html>
