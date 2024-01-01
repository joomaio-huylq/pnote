<div class="container-fluid px-3">
	<div class="row">
		<div class="col-12 d-flex">
			<div class="w-100">
				<div class="row">
					<div class="col-sm-4">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col mt-0">
										<a href="<?php echo $this->link_mynote; ?>">
											<h5 class="card-title">My Notes</h5>
										</a>
									</div>
									<div class="col-auto">
										<a data-bs-placement="top" 
											data-bs-toggle="modal" 
											data-bs-target="#noteNewModal"
											href="#" 
											data-link="<?php echo $this->link_shortcut_form;?>/0"
											class="stat align-middle ms-4 button-shortcut" 
											type="button">
											<i class="fa-solid fa-plus"></i>
										</a>
									</div>
								</div>
								<h3 class="mt-1 mb-3"><?php echo $this->countMyNote; ?> Notes | <?php echo $this->countMyShared; ?> Shared</h3>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col mt-0">
										<a href="<?php echo $this->link_filter;?>">
											<h5 class="card-title">My Filters</h5>
										</a>
									</div>

									<div class="col-auto">
										<a class="stat text-primary" href="<?php echo $this->link_form_filter ?>">
											<i class="fa-solid fa-plus"></i>
										</a>
									</div>
								</div>
								<h3 class="mt-1 mb-3"><?php echo $this->countMyFilter; ?> Filters</h3>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col mt-0">
										<a href="<?php echo $this->link_sharenote;?>">
											<h5 class="card-title">My Shares</h5>
										</a>
									</div>

									<div class="col-auto">
										<a data-bs-placement="top" 
											data-bs-toggle="modal" 
											data-bs-target="#noteNewModal"
											href="#" 
											data-link="<?php echo $this->link_shortcut_form;?>/0"
											class="stat align-middle ms-4 button-shortcut" 
											type="button">
											<i class="fa-solid fa-plus"></i>
										</a>
									</div>
								</div>
								<h3 class="mt-1 mb-3"><?php echo $this->countShare; ?> Notes</h3>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
        <div class="col-xl-12 col-xxl-12">
			<div class="card flex-fill w-100">
				<div class="card-header d-flex align-items-center">
					<h5 class="card-title mb-0">Shortcut</h5>
					<a data-bs-placement="top" 
						data-bs-toggle="modal" 
						data-bs-target="#shortcutModel"
						href="#" 
						data-link="<?php echo $this->link_shortcut_form;?>/0"
						class="stat align-middle ms-4 button-shortcut" 
						type="button">
						<i class="fa-solid fa-plus"></i>
					</a>
				</div>
				<div class="card-body pt-0">
					<div class="row shortcut-list">
						<?php foreach($this->shortcuts as $shortcut) : ?>
							<div class="col-4 mb-3">
								<?php if(isset($shortcut['childs']) && $shortcut['childs']): ?>
									<div>
										<h4 style="min-height: 21px;" class="mb-3"><?php echo $shortcut['group']?></h4>
										<table class="table border-top border-1">
											<tbody>
												<?php foreach($shortcut['childs'] as $child):?>
													<tr>
														<td><a href="<?php echo $child['link'] ?>"><?php echo $child['name'] ?></a></td>
														<td class="action" width="100">
															<a class="fs-4 me-2 button-shortcut" 
																data-bs-placement="top" 
																data-bs-toggle="modal" 
																data-bs-target="#shortcutModel"
																data-link="<?php echo $this->link_shortcut_form. '/'. $child['id'];?>"
																data-id="<?php echo $child['id'];?>" 
																data-name_shortcut ="<?php echo $child['name'];?>" 
																data-link_shortcut ="<?php echo $child['link'];?>"
																data-group_shortcut ="<?php echo $child['group'];?>"
															>
																<i class="fa-solid fa-pen-to-square"></i>
															</a>
															<a class="fs-4 remove-shortcut" data-id="<?php echo $child['id'];?>" >
																<i class="fa-solid fa-trash"></i>
															</a>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								<?php else: ?>
									<table class="table border-top border-1">
										<tbody>
											<tr>
												<td>
													<a href="<?php echo $shortcut['link']?>"><?php echo $shortcut['name']?></a>
												</td>
												<td class="action" width="100">
													<a class="fs-4 me-2 button-shortcut" 
														data-bs-placement="top" 
														data-bs-toggle="modal" 
														data-bs-target="#shortcutModel"
														data-link="<?php echo $this->link_shortcut_form. '/'. $shortcut['id'];?>"
														data-id="<?php echo $shortcut['id'];?>" 
														data-name_shortcut ="<?php echo $shortcut['name'];?>" 
														data-link_shortcut ="<?php echo $shortcut['link'];?>"
														data-group_shortcut ="<?php echo $shortcut['group'];?>"
													>
														<i class="fa-solid fa-pen-to-square"></i>
													</a>
													<a class="fs-4 remove-shortcut" data-id="<?php echo $shortcut['id'];?>" >
														<i class="fa-solid fa-trash"></i>
													</a>
												</td>
											</tr>
										</tbody>
									</table>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->renderWidget('shortcut::form');?>
<?php echo $this->render('pnote.javascript');?>
<?php echo $this->renderWidget('note::backend.popup_new'); ?>