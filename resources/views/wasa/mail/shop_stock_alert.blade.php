@if(isset($shop_product))
<p style="margin:0">商品名稱：{{$shop_product->name}}</p>
<p style="margin:0">商品編號：{{$shop_product->no}}</p>
@if(isset($shop_product_spec))
<p style="margin:0">商品規格：{{$spec_name}}</p>
@endif
@endif
此商品已缺貨，請盡快補貨。
