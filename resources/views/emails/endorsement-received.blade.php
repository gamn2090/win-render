<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>You've been endorsed on WIN</title>
</head>
<body style="font-family: Helvetica, Arial, sans-serif; color: #151515; padding: 24px;">
  <p>Hi {{ $vendorName }},</p>
  @php $typeList = count($typeNames) ? ' for: <strong>' . implode(', ', array_map('e', $typeNames)) . '</strong>' : ''; @endphp
  <p>Congratulations! <strong>{{ $endorserName }}</strong> endorsed you on Wedding Insiders Network{!! $typeList !!}.</p>
  <p>Peer endorsements help grow your WINfluence score and build trust with couples browsing your storefront.</p>
  <p>— The WIN Team</p>
</body>
</html>
