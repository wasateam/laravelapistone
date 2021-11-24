<p>New Contact Request</p>

@isset($contact_request->name&&fields['name'])
<p>Name</p>
<p>{{$contact_request->name}}</p>
@endisset

@isset($contact_request->email&&fields['email'])
<p>Email</p>
<p>{{$contact_request->email}}</p>
@endisset

@isset($contact_request->tel&&fields['tel'])
<p>Tel</p>
<p>{{$contact_request->tel}}</p>
@endisset

@isset($contact_request->company_name&&fields['company_name'])
<p>Company</p>
<p>{{$contact_request->company_name}}</p>
@endisset

@isset($contact_request->budget&&fields['budget'])
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

@isset($contact_request->remark&&fields['remark'])
<p>Remark</p>
<p>{{$contact_request->remark}}</p>
@endisset

@isset($contact_request->ip&&fields['ip'])
<p>IP</p>
<p>{{$contact_request->ip}}</p>
@endisset
