<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Chat - Customer Module</title>
    {{--<link type="text/css" rel="stylesheet" href="style.css" />--}}
</head>

<style type="text/css">

    /* CSS Document */
    body {
        font:12px arial;
        color: #222;
        text-align:center;
        padding:35px; }

    form, p, span {
        margin:0;
        padding:0; }

    input { font:12px arial; }

    a {
        color:#0000FF;
        text-decoration:none; }

    a:hover { text-decoration:underline; }

    #wrapper, #loginform {
        margin:0 auto;
        padding-bottom:25px;
        background:#EBF4FB;
        width:504px;
        border:1px solid #ACD8F0; }

    #loginform { padding-top:18px; }

    #loginform p { margin: 5px; }

    /*#chatbox {*/
    /*text-align:left;*/
    /*margin:0 auto;*/
    /*margin-bottom:25px;*/
    /*padding:10px;*/
    /*background:#fff;*/
    /*height:270px;*/
    /*width:430px;*/
    /*border:1px solid #ACD8F0;*/
    /*overflow:auto; }*/

    #usermsg {
        width: 222px;
        /*border:1px solid #ACD8F0; */
    }

    #submit { width: 60px; }


    .error { color: #ff0000; }

    #menu { padding:12.5px 25px 12.5px 25px; }

    .welcome { float:left; }

    .logout { float:right; }

    .msgln { margin:0 0 2px 0; }



    /*new chat css*/

    @import url(http://weloveiconfonts.com/api/?family=typicons);
    [class*="typicons-"]:before {
        font-family: 'Typicons', sans-serif;
    }

    .module {
        width: 300px;
        margin: 20px auto;
    }

    .top-bar {
        background: #666;
        color: white;
        padding: 0.5rem;
        position: relative;
        overflow: hidden;
    }

    .top-bar h1 {
        display: inline;
        font-size: 1.1rem;
    }

    .top-bar .typicons-message {
        display: inline-block;
        padding: 4px 5px 2px 5px;
    }

    .top-bar .typicons-minus {
        position: relative;
        top: 3px;
    }

    .top-bar .left {
        float: left;
    }

    .top-bar .right {
        float: right;
        padding-top: 5px;
    }

    .top-bar > * {
        position: relative;
    }

    .top-bar::before {
        content: "";
        position: absolute;
        top: -100%;
        left: 0;
        right: 0;
        bottom: -100%;
        opacity: 0.25;
        background: radial-gradient(white, black);
        animation: pulse 1s ease alternate infinite;
    }

    .discussion {
        list-style: none;
        background: #e5e5e5;
        margin: 0;
        padding: 0 0 50px 0;
    }

    .discussion li {
        padding: 0.5rem;
        overflow: hidden;
        display: flex;
    }

    .discussion .avatar {
        width: 40px;
        position: relative;
    }

    .discussion .avatar img {
        display: block;
        width: 100%;
    }

    .other .avatar:after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        width: 0;
        height: 0;
        border: 5px solid white;
        border-left-color: transparent;
        border-bottom-color: transparent;
    }

    .self {
        justify-content: flex-end;
        align-items: flex-end;
    }

    .self .messages {
        order: 1;
        text-align: right;
        border-bottom-right-radius: 0;
    }
    .other .messages {
        text-align: left;
    }

    .self .avatar {
        order: 2;
    }

    .self .avatar:after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 0;
        border: 5px solid white;
        border-right-color: transparent;
        border-top-color: transparent;
        box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }

    .messages {
        background: white;
        padding: 10px;
        border-radius: 2px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .messages p {
        font-size: 0.8rem;
        margin: 0 0 0.2rem 0;
    }

    .messages time {
        font-size: 0.7rem;
        color: #ccc;
    }

    @keyframes pulse {
        from {
            opacity: 0;
        }
        to {
            opacity: 0.5;
        }
    }
</style>



<section class="module">

    <header class="top-bar">

        <div class="left">
            <span class="icon typicons-message"></span>
            <h1>Hangouts</h1>
        </div>

        <div class="right">
            <span class="icon typicons-minus"></span>
            <span class="icon typicons-times"></span>
        </div>

    </header>

    <ol  id="chatbox" class="discussion">
        <li class="other">
            <div class="avatar">
                <img src="{{'/images/showavatar.php.jpeg'}}" />
            </div>
            <div class="messages">
                <p>{{ $initialGreetings }}</p>
            </div>
        </li>
    </ol>
    {{--<div id="chatbox"></div>--}}
    <section id="suggslider" class="regular slider">

    </section>

    <form id="msgform" name="message" action="">
        <input name="usermsg" type="text" id="usermsg" size="63" />
        <input name="submitmsg" type="submit"  id="submitmsg" value="Send" />
    </form>

</section>




<script
        src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="/css/slick.css"/>
<script type="text/javascript" src="/js/slick.min.js"></script>
<script type="text/javascript">
    // jQuery Document
    //    $(document).ready(function(){
    $(function () {
        //alert('uiop');
        $('#msgform').on('submit', function (e) {
            e.preventDefault();
//            alert('sssssss');
            var $this = $(this);
            var userinput = $('#usermsg').val();
            $('#chatbox').append('<li class="self"><div class="avatar"><img src="{{'/images/default-avatar.jpg'}}" /></div><div class="messages"><p>'+userinput+'</p></div></li>');
            $('#usermsg').val('');
            $.ajax({
                type      : 'GET',
                url       : '/chat/v1/chatviewwithdata',
                data      : { 'userinput': userinput },
                dataType  : 'json',
                success   : function(res) {
//                    alert(res.content);
                    console.log(res.content);
                    var content = "Found Nothing";
                    if (res.content !='NULL' && res.content)
                        content = res.content;
                    $('#chatbox').append('<li class="other"><div class="avatar"><img src="{{'/images/showavatar.php.jpeg'}}" /></div><div class="messages"><p>'+content+'</p></div></li>');

                    $('#suggslider').empty();
                    if(typeof res.suggestion != 'undefined')
                    {

                        for (var i in res.suggestion) {
                            var ss = res.suggestion[i].split("K::V");
                            $('#suggslider').append('<input type="button" class="suggestionbtn" value="'+ss[0]+'" data-display="'+ss[0]+'"  data-send="'+ss[1]+'" style="text-align:right;"/>');

                        }


                    }
                    var t = document.getElementById('chatbox');
                    t.scrollTop = t.scrollHeight;

                }
            });
        });
//        });

        $('body').on('click', '.suggestionbtn', function (e) {
//            alert('bal');
            var $this = $(this);
            var userdisplay = $this.attr('data-display');
            var userinput = $this.attr('data-send');
            $('#chatbox').append('<li class="self"><div class="avatar"><img src="{{'/images/default-avatar.jpg'}}" /></div><div class="messages"><p>'+userinput+'</p></div></li>');
//            $('#usermsg').val('');
            $.ajax({
                type      : 'GET',
                url       : '/chat/v1/chatviewwithdata',
                data      : { 'userinput': userinput },
                dataType  : 'json',
                success   : function(res) {
//                    alert(res.content);
                    console.log(res.content);
                    var content = "Found Nothing";
                    if (res.content !='NULL' && res.content)
                        content = res.content;
                    $('#chatbox').append('<li class="other"><div class="avatar"><img src="{{'/images/showavatar.php.jpeg'}}" /></div><div class="messages"><p>'+content+'</p></div></li>');
                    $('#suggslider').empty();
                    if(typeof res.suggestion != 'undefined')
                    {

                        for (var i in res.suggestion) {
                            var ss = res.suggestion[i].split("K::V");
                            $('#suggslider').append('<input type="button" class="suggestionbtn" value="'+ss[0]+'" data-display="'+ss[0]+'"  data-send="'+ss[1]+'" style="text-align:right;"/>');

                        }


                    }
                    var t = document.getElementById('chatbox');
                    t.scrollTop = t.scrollHeight;

                }
            });
        });
        $(".regular").slick({
//            dots: true,
//            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 3
        });
    });
</script>
</body>
</html>