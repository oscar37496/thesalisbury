@if( isset($tag['id'])) <td>{{{ $tag['user']['first_name'].
' '.$tag['user']['middle_name'].
(isset($tag['user']['middle_name'])?' ':'').
$tag['user']['last_name'] }}}</td>
<td>{{{ $tag['id'] }}}</td>
<td>{{{ $tag['description'] }}}</td>
<td> @if( $tag['is_activated'])
Active
<button type="button" onclick="loadAjax('{{{ $tag['id'] }}}', '/account/admin/cards/{{{ $tag['id'] }}}/deactivate')">
	Deactivate
</button> @else
Deactivated
<button type="button" onclick="loadAjax('{{{ $tag['id'] }}}', '/account/admin/cards/{{{ $tag['id'] }}}/activate')">
	Activate
</button> @endif </td>
@else
<td></td>
<td></td>
<td>Manual Transactions</td>
<td></td>
@endif
<td>{{{ $tag['count'] }}}</td>
<td>{{{ money_format('%n', $tag['total'] / 100 ) }}}</td>
