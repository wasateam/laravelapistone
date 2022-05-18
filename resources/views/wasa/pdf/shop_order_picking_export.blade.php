<!DOCTYPE html>
<html>

<head>
  <style>
     :root {
      --fonturl: url({{storage_path('fonts/TaipeiSansTCBeta-Regular.ttf')}});
    }

    @font-face {
      font-family: 'TaipeiSansTCBeta';
      src: var(--fonturl);
      font-weight: 400;
      font-style: normal;
    }

    td{
      word-break:break-all; word-wrap:break-word;
    }

  </style>
</head>

<body>
  @foreach ($shop_orders as $shop_order_key => $shop_order)
    @if($shop_order_key > 0)
     <div class="page-break"></div>
    @endif
    <div class="pdf-file">
      <div class="wrap">
        <div class="header">
          <div>
            <h1>
              放心初蔬果網訂購單（內部留存）
            </h1>
          </div>
        </div>
        <table width="100%">
          <tr class="table-item">
            <td class="title w-10per">
              活動
            </td>
            <td class="content w-40per">
            {{$shop_order['activity']}}
            </td>
            <td class="title w-10per">配送時段</td>
            <td class="content w-40per">{{$shop_order['delivery_time']}}</td>
          </tr>
          <tr class="table-item">
            <td class="title w-10per">訂單編號</td>
            <td class="content w-40per">{{$shop_order['no']}}</td>
            <td class="title w-10per">訂購資訊</td>
            <td class="content w-40per">{{$shop_order['orderer']}}</td>
          </tr>
          <tr class="table-item">
            <td class="title w-10per gray-bg">收件姓名</td>
            <td class="content w-40per gray-bg">{{$shop_order['receiver']}}</td>
            <td class="title w-10per gray-bg">訂購時間</td>
            <td class="content w-40per gray-bg">{{$shop_order['order_date']}}</td>
          </tr>
          <tr class="table-item">
            <td class="title w-10per gray-bg">電話</td>
            <td class="content w-40per gray-bg">{{$shop_order['receiver_tel']}}</td>
            <td class="title w-10per gray-bg">收件方式</td>
            <td class="content w-40per gray-bg">{{$shop_order['receive_way']}}</td>
          </tr>
        </table>
        <table width="100%">
          <tr class="table-item">
            <td class="title w-10per gray-bg">收件地址</td>
            <td class="content w-90per gray-bg">{{$shop_order['receive_address']}}</td>
          </tr>
          <tr class="table-item">
            <td class="title w-10per gray-bg">收件備註</td>
            <td class="content w-90per gray-bg">{{$shop_order['receive_remark']}}</td>
          </tr>
        </table>
        <div>
          <div class="header">
            <h1>訂購商品</h1>
          </div>
        </div>
        <table width="100%" style="table-layout: fixed;">
          <tr class="table-item">
            <td class="title" style="width:5%;">序號</td>
            <td class="title" style="width:20%;">商品編號</td>
            <td class="title" style="width:30%;">品名</td>
            <td class="title" style="width:10%;">規格</td>
            <td class="title" style="width:17%;">重量/容量</td>
            <td class="title" style="width:8%;">儲位</td>
            <td class="title" style="width:10%;">數量</td>
          </tr>
          @foreach ($shop_order['shop_order_shop_products'] as $shop_order_shop_product_key => $shop_order_shop_product)
            <tr class="table-item">
              <td class="content" style="width:5%;">{{$shop_order_shop_product_key + 1}}</td>
              <td class="content" style="width:20%;">{{$shop_order_shop_product['no']}}</td>
              <td class="content" style="width:30%;">{{$shop_order_shop_product['name']}}</td>
              <td class="content" style="width:10%;">{{$shop_order_shop_product['spec']}}</td>
              <td class="content" style="width:17%;">{{$shop_order_shop_product['weight_capacity']}} {{$shop_order_shop_product['weight_capacity_unit']}} ± 10%</td>
              <td class="content" style="width:8%;">{{$shop_order_shop_product['storage_space']}}</td>
              <td class="content" style="width:10%;">{{$shop_order_shop_product['count']}}</td>
            </tr>
          @endforeach
        </table>
      </div>
    </div>
    <div class="page-break"></div>
    <div class="pdf-file">
      <div class="wrap">
        <div class="header">
          <div>
            <h1>
              放心初蔬果網訂購單
            </h1>
          </div>
        </div>
        <table width="100%">
          <tr class="table-item">
            <td class="title w-10per">
              活動
            </td>
            <td class="content w-40per">
            {{$shop_order['activity']}}
            </td>
            <td class="title w-10per">配送時段</td>
            <td class="content w-40per">{{$shop_order['delivery_time']}}</td>
          </tr>
          <tr class="table-item">
            <td class="title w-10per">訂單編號</td>
            <td class="content w-40per">{{$shop_order['no']}}</td>
            <td class="title w-10per">訂購資訊</td>
            <td class="content w-40per">{{$shop_order['orderer_encode']}}</td>
          </tr>
          <tr class="table-item">
            <td class="title w-10per gray-bg">收件姓名</td>
            <td class="content w-40per gray-bg">{{$shop_order['receiver_encode']}}</td>
            <td class="title w-10per gray-bg">訂購時間</td>
            <td class="content w-40per gray-bg">{{$shop_order['order_date']}}</td>
          </tr>
          <tr class="table-item">
            <td class="title w-10per gray-bg">電話</td>
            <td class="content w-40per gray-bg">{{$shop_order['receiver_tel_encode']}}</td>
            <td class="title w-10per gray-bg">收件方式</td>
            <td class="content w-40per gray-bg">{{$shop_order['receive_way']}}</td>
          </tr>
        </table>
        <table width="100%">
          <tr class="table-item">
            <td class="title w-10per gray-bg">收件地址</td>
            <td class="content w-90per gray-bg">{{$shop_order['receive_address']}}</td>
          </tr>
          <tr class="table-item">
            <td class="title w-10per gray-bg">收件備註</td>
            <td class="content w-90per gray-bg">{{$shop_order['receive_remark']}}</td>
          </tr>
        </table>
        <div>
          <div class="header">
            <h1>訂購商品</h1>
          </div>
        </div>
        <table width="100%" style="table-layout: fixed;">
          <tr class="table-item">
            <td class="title" style="width:5%;">序號</td>
            <td class="title" style="width:20%;">商品編號</td>
            <td class="title" style="width:35%;">品名</td>
            <td class="title" style="width:10%;">規格</td>
            <td class="title" style="width:15%;">重量/容量</td>
            <td class="title" style="width:15%;">數量</td>
          </tr>
          @foreach ($shop_order['shop_order_shop_products'] as $shop_order_shop_product_key => $shop_order_shop_product)
            <tr class="table-item">
              <td class="content" style="width:5%;">{{$shop_order_shop_product_key + 1}}</td>
              <td class="content" style="width:20%;">{{$shop_order_shop_product['no']}}</td>
              <td class="content" style="width:35%;">{{$shop_order_shop_product['name']}}</td>
              <td class="content" style="width:10%;">{{$shop_order_shop_product['spec']}}</td>
              <td class="content" style="width:10%;">{{$shop_order_shop_product['weight_capacity']}} {{$shop_order_shop_product['weight_capacity_unit']}} ± 10%</td>
              <td class="content" style="width:15%;">{{$shop_order_shop_product['count']}}</td>
            </tr>
            @endforeach
          </table>
      </div>
    </div>
  @endforeach
</body>

</html>
<style>
.page-break {
    page-break-after: always;
}

* {
    font-family: TaipeiSansTCBeta;
}

.pdf-file {
    padding: 0px 10px;
}
.wrap {
    border: 1px solid #222;
}
.header {
    height: 32px;
    background-color: #efeeee;
    color: #393d43;
    padding: 2px 2px;
}
.title {
    padding: 2px 2px;
    font-size: 12px;
}
.w-10per {
    width: 10%;
}
.w-40per {
    width: 40%;
}
.w-90per {
    width: 90%;
}
.content {
    padding: 2px 2px;
    font-size: 12px;
}
td {
    border: 1px solid #222;
}
td.gray-bg {
  background-color: #ccc;
}
h1 {
    font-size: 14px;
}
h2 {
    font-size: 12px;
}

</style>
