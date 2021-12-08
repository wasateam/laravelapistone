@if(isset($contact_request['name'])&&in_array('name',$fields))
<h4 style="margin:0">Name:</h4>
<p style="margin:0">{{$contact_request['name']}}</p>
<br>
@endif

@if(isset($contact_request['email'])&&in_array('email',$fields))
<h4 style="margin:0">Email:</h4>
<p style="margin:0">{{$contact_request['email']}}</p>
<br>
@endif

@if(isset($contact_request['tel'])&&in_array('tel',$fields))
<h4 style="margin:0">Tel:</h4>
<p style="margin:0">{{$contact_request['tel']}}</p>
<br>
@endif

@if(isset($contact_request['company_name'])&&in_array('company_name',$fields))
<h4 style="margin:0">Company:</h4>
<p style="margin:0">{{$contact_request['company_name']}}</p>
<br>
@endif

@if(isset($contact_request['budget'])&&in_array('budget',$fields))
<h4 style="margin:0">預算</h4>
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
<br>
@endif

@if(isset($contact_request['remark'])&&in_array('remark',$fields))
<h4 style="margin:0">Description:</h4>
<p style="margin:0">{{$contact_request['remark']}}</p>
<br>
@endif

@if(isset($contact_request['ip'])&&in_array('ip',$fields))
<h4 style="margin:0">IP:</h4>
<p style="margin:0">{{$contact_request['ip']}}</p>
<br>
@endif
