<div class="table-resposive">
	<?=$object->tabs?>
	<h2 class="sub-header">Список привелегий </h2>
	<form method="post" action="" class="form-horizontal" >
		<table class="table table-striped">
		<thead>
		<tr>
			<th> Привелегии</th>
			<? foreach ($object->roles_with_privs as $rwp ) :?>
			<th><?=$rwp['role']['role_name']?> </th>
			<? endforeach?>
		</tr>
		</thead>
		<tbody>
			<? foreach($object->privs as $priv) : ?>
			<tr>
				<td><?=$priv['priv_description']?></td>
				<? foreach($object->roles_with_privs as $rwp2) :?>
				<?$checked = ''?>
					<? foreach($rwp2['privs'] as $rpriv) :?>
						<? if($priv['priv_id'] == $rpriv['priv_id']): 
								$checked = 'checked';
								$break;
							 endif?>
					<? endforeach;?>
				<td><input type="checkbox" 
							name="priv_<?=$rwp2['role']['role_id'] . '_' . $priv['priv_id']?>" 
							<?=$checked?>>
				</td>
					
				<? endforeach?>
			</tr>
			<? endforeach?>
		</tbody>
		</table>
		<div class="form-group">
			<div class="col-lg-10">
				<button class="btn btn-primary" type="submit">Сохранить изменения</button>
			</div>
		</div>	
	</form>
	<br/>
	
</div>














