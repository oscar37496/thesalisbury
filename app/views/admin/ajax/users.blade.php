<td><img src="https://graph.facebook.com/v2.2/{{{ $user['id'] }}}/picture?height=45&amp;width=45" class="img-circle" alt="User Image"></td>
<td>{{{ $user['first_name'] }}}</td>
<td>{{{ $user['middle_name'] }}}</td>
<td>{{{ $user['last_name'] }}}</td>
<td> @if($user['is_activated'])
	Activated
	<button type="button" onclick="loadAjax('{{{ $user['id'] }}}', '/account/admin/users/{{{ $user['id'] }}}/deactivate')">
		Deactivate
	</button> @else
	Deactivated
	<button type="button" onclick="loadAjax('{{{ $user['id'] }}}', '/account/admin/users/{{{ $user['id'] }}}/activate')">
		Activate
	</button> @endif </td>
<td> @if($user['is_social'])
	Social
	<button type="button" onclick="loadAjax('{{{ $user['id'] }}}', '/account/admin/users/{{{ $user['id'] }}}/remove-social')">
		Remove Social
	</button> @else
	Not Social
	<button type="button" onclick="loadAjax('{{{ $user['id'] }}}', '/account/admin/users/{{{ $user['id'] }}}/make-social')">
		Make Social
	</button> @endif </td>
<td>{{{ money_format('%n', $total_spent_last_week / 100 ) }}}</td>
<td>{{{ money_format('%n', $total_spent / 100 ) }}}</td>
<td>{{{ money_format('%n', $user['balance'] / 100 ) }}}</td>
