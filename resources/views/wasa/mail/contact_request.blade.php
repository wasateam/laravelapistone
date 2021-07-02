<p>有新的聯絡請求喔</p>

@isset($contact_request->name)
<p>大名</p>
<p>{{$contact_request->name}}</p>
@endisset

@isset($contact_request->email)
<p>信箱</p>
<p>{{$contact_request->email}}</p>
@endisset

@isset($contact_request->tel)
<p>聯絡電話</p>
<p>{{$contact_request->tel}}</p>
@endisset

@isset($contact_request->company_name)
<p>公司/單位</p>
<p>{{$contact_request->company_name}}</p>
@endisset

@isset($contact_request->budget)
<p>預算</p>
@if($contact_request->budget=='nolimit')
<p>老子有錢</p>
@endif
@if($contact_request->budget=='2')
<p>2萬以下</p>
@endif
@if($contact_request->budget=='2-10')
<p>2萬~10萬</p>
@endif
@if($contact_request->budget=='10-50')
<p>10萬~50萬</p>
@endif
@endisset

@isset($contact_request->remark)
<p>備註</p>
<p>{{$contact_request->remark}}</p>
@endisset

@isset($contact_request->ip)
<p>IP</p>
<p>{{$contact_request->ip}}</p>
@endisset