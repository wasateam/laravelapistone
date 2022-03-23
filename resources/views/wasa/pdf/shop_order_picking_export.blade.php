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
            <td class="title w-10per">收件姓名</td>
            <td class="content w-40per">{{$shop_order['receiver']}}</td>
            <td class="title w-10per">訂購時間</td>
            <td class="content w-40per">{{$shop_order['order_date']}}</td>
          </tr>
          <tr class="table-item">
            <td class="title w-10per">電話</td>
            <td class="content w-40per">{{$shop_order['receiver_tel']}}</td>
            <td class="title w-10per">收件方式</td>
            <td class="content w-40per">{{$shop_order['receive_way']}}</td>
          </tr>
        </table>
        <table width="100%">
          <tr class="table-item">
            <td class="title w-10per">收件地址</td>
            <td class="content w-90per">{{$shop_order['receive_address']}}</td>
          </tr>
          <tr class="table-item">
            <td class="title w-10per">收件備註</td>
            <td class="content w-90per">{{$shop_order['receive_remark']}}</td>
          </tr>
        </table>
        <div>
          <div class="header">
            <h1>訂購商品</h1>
          </div>
        </div>
        <table width="100%">
          <tr class="table-item">
            <td class="title">序號</td>
            <td class="title">品名</td>
            <td class="title">規格</td>
            <td class="title">重量kg+_10%</td>
            <td class="title">儲位</td>
            <td class="title">數量</td>
          </tr>
          @foreach ($shop_order['shop_order_shop_products'] as $shop_order_shop_product_key => $shop_order_shop_product)
            <tr class="table-item">
            <td class="content ">{{$shop_order_shop_product_key + 1}}</td>
              <td class="content">{{$shop_order_shop_product['name']}}</td>
              <td class="content">{{$shop_order_shop_product['spec']}}</td>
              <td class="content ">{{$shop_order_shop_product['weight_capacity']}}</td>
              <td class="content">{{$shop_order_shop_product['storage_space']}}</td>
              <td class="content">{{$shop_order_shop_product['count']}}</td>
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
            <td class="content w-40per">{{$shop_order['orderer']}}</td>
          </tr>
          <tr class="table-item">
            <td class="title w-10per">收件姓名</td>
            <td class="content w-40per">{{$shop_order['receiver']}}</td>
            <td class="title w-10per">訂購時間</td>
            <td class="content w-40per">{{$shop_order['order_date']}}</td>
          </tr>
          <tr class="table-item">
            <td class="title w-10per">電話</td>
            <td class="content w-40per">{{$shop_order['receiver_tel']}}</td>
            <td class="title w-10per">收件方式</td>
            <td class="content w-40per">{{$shop_order['receive_way']}}</td>
          </tr>
        </table>
        <table width="100%">
          <tr class="table-item">
            <td class="title w-10per">收件地址</td>
            <td class="content w-90per">{{$shop_order['receive_address']}}</td>
          </tr>
          <tr class="table-item">
            <td class="title w-10per">收件備註</td>
            <td class="content w-90per">{{$shop_order['receive_remark']}}</td>
          </tr>
        </table>
        <div>
          <div class="header">
            <h1>訂購商品</h1>
          </div>
        </div>
        <table width="100%">
          <tr class="table-item">
            <td class="title">序號</td>
            <td class="title">品名</td>
            <td class="title">規格</td>
            <td class="title">重量kg+_10%</td>
            <td class="title">數量</td>
          </tr>
          @foreach ($shop_order['shop_order_shop_products'] as $shop_order_shop_product_key => $shop_order_shop_product)
            <tr class="table-item">
            <td class="content ">{{$shop_order_shop_product_key + 1}}</td>
              <td class="content">{{$shop_order_shop_product['name']}}</td>
              <td class="content">{{$shop_order_shop_product['spec']}}</td>
              <td class="content ">{{$shop_order_shop_product['weight_capacity']}}</td>
              <td class="content">{{$shop_order_shop_product['count']}}</td>
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
    padding: 0px 24px;
}
.wrap {
    border: 1px solid #eaedf1;
}
.header {
    height: 32px;
    background-color: #efeeee;
    color: #393d43;
    padding: 8px 10px;
}
.title {
    padding: 8px 10px;
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
    padding: 8px 10px;
    font-size: 12px;
}
td {
    border: 1px solid #eaedf1;
}
h1 {
    font-size: 14px;
}
h2 {
    font-size: 12px;
}

</style>
