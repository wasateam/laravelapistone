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
  <div class="pdf-file">
    <div class="wrap">
      <div class="header">
        <div>
          <h1>
            放心初蔬果網訂購單（內部留存）
          </h1>
        </div>
      </div>
      <div class="item">
        <div class="title w-10per">
          活動
        </div>
        <div class="content w-40per">
          {{$shop_order['activity']}}
        </div>
        <div class="title w-10per">配送時段</div>
        <div class="content w-40per">{{$shop_order['delivery_time']}}</div>
      </div>
      <div class="item">
        <div class="title w-10per">訂單編號</div>
        <div class="content w-40per">{{$shop_order['no']}}</div>
        <div class="title w-10per">訂購資訊</div>
        <div class="content w-40per">{{$shop_order['orderer']}}</div>
      </div>
      <div class="item">
        <div class="title w-10per">收件姓名</div>
        <div class="content w-40per">{{$shop_order['receiver']}}</div>
        <div class="title w-10per">訂購時間</div>
        <div class="content w-40per">{{$shop_order['order_date']}}</div>
      </div>
      <div class="item">
        <div class="title w-10per">電話</div>
        <div class="content w-40per">{{$shop_order['receiver_tel']}}</div>
        <div class="title w-10per">收件方式</div>
        <div class="content w-40per">{{$shop_order['receive_way']}}</div>
      </div>
      <div class="item">
        <div class="title w-10per">收件地址</div>
        <div class="content w-90per">{{$shop_order['receive_address']}}</div>
      </div>
      <div class="item">
        <div class="title w-10per">收件備註</div>
        <div class="content w-90per">{{$shop_order['receive_remark']}}</div>
      </div>
      <div>
        <div class="header">
          <h1>訂購商品</h1>
        </div>
      </div>
      <table width="100%">
        <tr
          class="table-item"
          style="width:'20px'"
        >
          <td
            class="title"
            style="width:'20px'"
          >序號</td>
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
            放心初蔬果網訂購單（內部留存）
          </h1>
        </div>
      </div>
      <div class="item">
        <div class="title w-10per">
          活動
        </div>
        <div class="content w-40per">
          {{$shop_order['activity']}}
        </div>
        <div class="title w-10per">配送時段</div>
        <div class="content w-40per">{{$shop_order['delivery_time']}}</div>
      </div>
      <div class="item">
        <div class="title w-10per">訂單編號</div>
        <div class="content w-40per">{{$shop_order['no']}}</div>
        <div class="title w-10per">訂購資訊</div>
        <div class="content w-40per">{{$shop_order['orderer']}}</div>
      </div>
      <div class="item">
        <div class="title w-10per">收件姓名</div>
        <div class="content w-40per">{{$shop_order['receiver']}}</div>
        <div class="title w-10per">訂購時間</div>
        <div class="content w-40per">{{$shop_order['order_date']}}</div>
      </div>
      <div class="item">
        <div class="title w-10per">電話</div>
        <div class="content w-40per">{{$shop_order['receiver_tel']}}</div>
        <div class="title w-10per">收件方式</div>
        <div class="content w-40per">{{$shop_order['receive_way']}}</div>
      </div>
      <div class="item">
        <div class="title w-10per">收件地址</div>
        <div class="content w-90per">{{$shop_order['receive_address']}}</div>
      </div>
      <div>
        <div class="header">
          <h1>訂購商品</h1>
        </div>
      </div>
      <table width="100%">
        <tr
          class="table-item"
          style="width:'20px'"
        >
          <td
            class="title"
            style="width:'20px'"
          >序號</td>
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
      padding: 72px;
    }
    .wrap {
      border: 1px solid #eaedf1;
    }
    .header {
      height: 32px;
      background-color: #656d79;
      color: #393d43;
      padding: 8px 10px;
    }
    .item {
      display: flex;
    }
    .title {
      padding: 8px 10px;
      font-size: 10px;
      font-weight: 600;
    }
    .item .title {
      display: flex;
      justify-content: center;
      align-items: center;
      border: 1px solid #eaedf1;
    }
    .table-item .title {
      text-align: center;
      vertical-align: middle;
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
      font-size: 10px;
    }
    .item .content {
      display: flex;
      align-items: center;
      border: 1px solid #eaedf1;
    }
    .table-item {
      border-collapse: collapse;
    }
    td {
      border: 1px solid #eaedf1;
    }

    tr td:nth-last-child(1) {
      border-right: none;
    }
    tr td:nth-child(1) {
      border-left: none;
    }
    table tr:nth-last-child(1) {
      border-bottom: none;
    }
    .table-item .content:nth-child(1),
    .table-item .content:nth-child(4) {
      text-align: right;
    }
    h1 {
      font-size: 12px;
    }
    h2 {
      font-size: 10px;
    }
</style>
