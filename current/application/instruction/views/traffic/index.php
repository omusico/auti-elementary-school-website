<?php
/**
 * 澳底國小網站程式
 *
 * LICENSE
 *
 * 本程式遵循GNU/GPL v3規範，詳情請見http://www.gnu.org/licenses/gpl.txt
 *
 * @copyright  2008 ottokang
 * @license    http://www.gnu.org/licenses/gpl.txt   GNU/GPL License 3
 */
?>
<?php $this->headTitle('交通資訊') ?>
<?php $this->headLink()->appendStylesheet(BASE_URL . 'template/css/traffic.css') ?>

<h1>交通資訊</h1>
<ul>
    <li>火車：於福隆火車站下車，再轉搭基隆客運</li>
    <li>客運：搭乘基隆客運（基隆─福隆）、國光客運（台北─宜蘭羅東線），到澳底下車</li>
    <li>開車：從八堵交流道或基隆出口，轉接（台2省）濱海公路。</li>
</ul>

<p>由Google提供的地圖：</p>
<div id="googleMap">
    <iframe title="澳底地圖" width="640" height="480" frameborder="1" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com.tw/maps?q=%E6%BE%B3%E5%BA%95%E5%9C%8B%E5%B0%8F&amp;ie=UTF8&amp;brcurrent=3,0x345d5ce8e584c6dd:0x9103200a1e847910&amp;sll=25.05488,121.92504&amp;sspn=0.006295,0.006295&amp;s=AARTsJrzK-jC-7fdwQkY5amoUsi6savfgw&amp;ll=25.052363,121.922278&amp;spn=0.018661,0.027466&amp;z=15&amp;output=embed">
    </iframe>
    <br />
    <div style="margin-top:0.5em;">
        <a class="external" href="http://maps.google.com.tw/maps?q=%E6%BE%B3%E5%BA%95%E5%9C%8B%E5%B0%8F&amp;ie=UTF8&amp;brcurrent=3,0x345d5ce8e584c6dd:0x9103200a1e847910&amp;sll=25.05488,121.92504&amp;sspn=0.006295,0.006295&amp;ll=25.052363,121.922278&amp;spn=0.018661,0.027466&amp;z=15&amp;source=embed" style="color:#0000FF;text-align:left" target="blank">
            查看較大的地圖
        </a>
    </div>
</div>