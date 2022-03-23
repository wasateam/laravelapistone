@if(isset($shop_product))
<h4 style="margin:0">商品名稱：</h4>
<p style="margin:0">{{$shop_product->name}}</p>
<h4 style="margin:0">商品編號：</h4>
<p style="margin:0">{{$shop_product->no}}</p>
@if(isset($shop_product_spec))
<h4 style="margin:0">商品規格：</h4>
<p style="margin:0">{{$spec_name}}</p>
@endif
@endif
此商品已缺貨，請盡快補貨。
