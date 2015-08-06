<?php
/**
 * conferenceplus
 * @author Robert Deutz <rdeutz@googlemail.com>
 * @package conferenceplus
 **/

// No direct access
defined('_JEXEC') or die;

$baseLayoutPath = JPATH_ROOT . '/media/conferenceplus/layouts';

$prog	= $this->programme;
$useTabs = count($prog) > 1;

$activeTab  = $this->input->get('tabid', 0);
$roomsCount = count($this->rooms);
?>
<div role="tabpanel">
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<?php foreach ($prog as $key => $p) : ?>
			<li role="presentation"<?php echo $activeTab == $key ? ' class="active"' : '' ;?>>
				<a href="#ptab<?php echo $key; ?>" role="tab" data-toggle="tab"><?php echo $p[0]->dayname; ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<?php foreach ($prog as $key => $p) : ?>
			<div role="tabpanel" class="tab-pane<?php echo $activeTab == $key ? ' active' : '' ;?>" id="ptab<?php echo $key; ?>">
				<br />
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="span2"><?php echo JText::_('COM_CONFERENCEPLUS_TIME'); ?></th>
							<?php foreach ($this->rooms as $room) : ?>
								<th class="span2">
									<?php echo $room->name; ?>
								</th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($p as $slot) : ?>
							<tr class="time slotype<?php echo $slot->slottype; ?>">
								<td class="time slotype<?php echo $slot->slottype; ?>">
									<?php echo substr($slot->stime, 0, 5); ?>&nbsp;-&nbsp;
									<?php echo substr($slot->etime, 0, 5); ?>
								</td>
								<?php if ($slot->slottype == 0 && ! empty($slot->sessionsOrdered)) : ?>
									<?php for($i = 0; $i < $roomsCount; $i++) : ?>
										<td class="slotype<?php echo $slot->slottype; ?>">
											<?php $session = $slot->sessionsOrdered[$i]; ?>
											<?php if (property_exists($session, 'tba')) : ?>
												<?php //echo $session->tba; ?>
											<?php else : ?>
												<?php echo JLayoutHelper::render('html.session', $session, $baseLayoutPath); ?>
											<?php endif; ?>
										</td>
									<?php endfor; ?>
								<?php else : ?>
									<td colspan="<?php echo $roomsCount; ?>" class="slotype<?php echo $slot->slottype; ?>">
										<?php if ( ! empty($slot->sessionsOrdered)) : ?>
											<?php $session = $slot->sessionsOrdered[0]; ?>
											<?php echo JLayoutHelper::render('html.session', $session, $baseLayoutPath); ?>
										<?php else : ?>
											<?php if ($slot->slottype == 4) : ?>
												<span class="glyphicon glyphicon-cutlery"></span>&nbsp;<?php echo $slot->name ; ?>
											<?php else  : ?>
												<?php echo $slot->name ; ?>
											<?php endif; ?>
										<?php endif; ?>
									</td>
								<?php endif; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endforeach; ?>
	</div>
</div>
