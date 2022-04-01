<p>Dear <strong>{{ $user_info['othername'] }}</strong>, </p>
<p> {{ $user_info['message'] }}</p>
<p>For any information please contact Email: {{env("companyInfoEmail",null)}} Help Desk: {{env("helpDeskContact",null)}}</p>
<p><strong>Thank you.</strong></p>
