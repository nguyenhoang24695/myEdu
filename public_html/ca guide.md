# Đặt quảng cáo quochoc.vn lên 123doc.org

Div hiển thị quảng cáo(đặt cùng với danh sách các bản ghi)

    <div class="item" id="ca_container"></div>

Code js

    <script type='text/javascript'>window._caOptions || function (e) {
        e._caOptions = {
            clientId : '123doc.org',
            keywords: '.box_content h1 strong',
    //                    customTemplate : '',
    //                    customCSS : '',
    //                    customClass : '',
            container: '#ca_container',
        };
        var n = e.location.protocol == "https:" ? "https:" : "http:";
        var r = document.createElement("script");
        r.type = "text/javascript";
        r.async = true;
        r.src = n + "//quochoc.vn/adsv1/colombo_ad_v1.js";
        var i = document.getElementsByTagName("script")[0];
        i.parentNode.insertBefore(r, i)
    }(window);</script>

