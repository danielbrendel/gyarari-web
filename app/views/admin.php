<div class="container">
	<div class="columns">
		<div class="column is-1"></div>

		<div class="column is-10">
			<div class="content-section">
				<h3>Admin area</h3>

				<h4>Approval pending</h3>

                <div>
                    @if (count($approvals) > 0)
                        @foreach ($approvals as $approval)
                            <a href="{{ asset('img/photos/' . $approval->get('photo_thumb')) }}">
                                <div class="photo" style="background-image: url('{{ asset('img/photos/' . $approval->get('photo_thumb')) }}');" onmouseover="document.getElementById('photo-overlay-{{ $approval->get('id') }}').style.display = 'block';" onmouseout="document.getElementById('photo-overlay-{{ $approval->get('id') }}').style.display = 'none';">
                                    <div class="photo-info-overlay fade-in" id="photo-overlay-{{ $approval->get('id') }}">
                                        <div class="photo-info-data">
                                            <div><a href="{{ url('/admin/photo/' . $approval->get('id') . '/approve?access=' . $access_token) }}">Approve</a> | <a href="{{ url('/admin/photo/' . $approval->get('id') . '/decline?access=' . $access_token) }}">Decline</a></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <i>No pending approvals</i>
                    @endif
                </div>

                <h4>Reports</h3>

                <div>
                @if (count($reports) > 0)
                        @foreach ($reports as $report)
                            <div>
                                Photo: <a href="{{ url('/photo/' . $report->get('photo')) }}" target="_blank">{{ $report->get('photo') }}</a>&nbsp;|&nbsp;
                                Count: {{ $report->get('count') }}&nbsp;|&nbsp;
                                <a href="{{ url('/admin/photo/' . $report->get('photo') . '/report/safe?access=' . $access_token) }}">Safe</a>&nbsp;|&nbsp;
                                <a href="{{ url('/admin/photo/' . $report->get('photo') . '/report/delete?access=' . $access_token) }}">Delete</a>
                            </div>
                        @endforeach
                    @else
                        <i>No reported photos</i>
                    @endif
                </div>
			</div>
		</div>

		<div class="column is-1"></div>
	</div>
</div>