@extends('layouts.app')

@section('title', ($audit->isDraft() ? 'Draft Video Source Audit' : 'View Video Source Audit') . ' - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>{{ $audit->isDraft() ? 'Draft Video Source Audit' : 'View Video Source Audit' }}</h1>
                <p>{{ $audit->videoSource->name ?? 'Video Source' }} - {{ $audit->audit_type_label }}</p>
            </div>

            <div style="display: flex; gap: 10px; align-items: center;">
                <a href="{{ route('nvr-systems.edit', $audit->videoSource) }}" class="btn btn-secondary">Back to Video Source</a>

                @if($audit->isDraft())
                    <button type="submit" form="audit-checklist-form" class="btn btn-secondary">Save Draft Audit</button>

                    <form method="POST" action="{{ route('video-source-audits.finalize', $audit) }}" style="margin: 0;" onsubmit="return confirm('Finalize and lock this audit? The checklist fields cannot be edited after finalization.');">
                        @csrf
                        <button type="submit" class="btn">Finalize Audit</button>
                    </form>
                @endif

                <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if($audit->isDraft())
        <div class="notice" style="margin-bottom: 20px;">
            <strong>Draft audit:</strong>
            Fill out the checklist, upload logs/clips/screenshots, then finalize the audit to lock the trust-chain record.
        </div>

        <form id="audit-checklist-form" method="POST" action="{{ route('video-source-audits.update', $audit) }}">
            @csrf
            @method('PUT')
        </form>

        @include('video-source-audits._form', [
            'audit' => $audit,
            'nvrSystem' => $audit->videoSource,
            'auditFormId' => 'audit-checklist-form',
        ])

        <div class="card">
            <button type="submit" form="audit-checklist-form" class="btn">Save Draft Audit</button>
            <a href="{{ route('nvr-systems.edit', $audit->videoSource) }}" class="btn btn-secondary">Back to Video Source</a>
        </div>
    @else
        <div class="notice" style="margin-bottom: 20px;">
            <strong>Append-only record:</strong>
            This audit is finalized and locked. Corrections or updates should be added as an addendum or attachment record.
        </div>

        <div class="card">
            <h2>Captured Trust Chain Data</h2>

            <div class="details">
                <div>
                    <div class="label">Performed By</div>
                    <div class="value">{{ $audit->performer_display_name }}</div>
                </div>

                <div>
                    <div class="label">Performed At</div>
                    <div class="value">{{ $audit->performed_at ? $audit->performed_at->format('M j, Y g:i A') : '-' }}</div>
                </div>

                <div>
                    <div class="label">Finalized At</div>
                    <div class="value">{{ $audit->locked_at ? $audit->locked_at->format('M j, Y g:i A') : '-' }}</div>
                </div>

                <div>
                    <div class="label">Finalized By</div>
                    <div class="value">{{ $audit->locked_by_display_name }}</div>
                </div>

                <div>
                    <div class="label">IP Address</div>
                    <div class="value">{{ $audit->ip_address ?? '-' }}</div>
                </div>

                <div>
                    <div class="label">Request</div>
                    <div class="value">{{ $audit->request_method ?? '-' }} {{ $audit->request_path ?? '-' }}</div>
                </div>

                <div style="grid-column: 1 / -1;">
                    <div class="label">User Agent</div>
                    <div class="value" style="font-family: monospace; font-size: 12px; word-break: break-all;">{{ $audit->user_agent ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>Audit Summary</h2>

            <div class="details">
                <div><div class="label">Audit Type</div><div class="value">{{ $audit->audit_type_label }}</div></div>
                <div><div class="label">Audit Status</div><div class="value">{{ $audit->audit_status_label }}</div></div>
                <div><div class="label">Organization</div><div class="value">{{ $audit->organization->name ?? '-' }}</div></div>
                <div><div class="label">Site</div><div class="value">{{ $audit->site->name ?? '-' }}</div></div>
                <div><div class="label">Video Source</div><div class="value">{{ $audit->videoSource->name ?? '-' }}</div></div>
                <div><div class="label">Next Audit Due</div><div class="value">{{ $audit->next_audit_due_at ? $audit->next_audit_due_at->format('M j, Y g:i A') : '-' }}</div></div>
            </div>
        </div>

        <div class="card">
            <h2>System Identity Observed</h2>

            <div class="details">
                <div><div class="label">Manufacturer</div><div class="value">{{ $audit->manufacturer_observed ?? '-' }}</div></div>
                <div><div class="label">Model</div><div class="value">{{ $audit->model_observed ?? '-' }}</div></div>
                <div><div class="label">Serial Number</div><div class="value">{{ $audit->serial_number_observed ?? '-' }}</div></div>
                <div><div class="label">Installation Date</div><div class="value">{{ $audit->installation_date ? $audit->installation_date->format('M j, Y') : '-' }}</div></div>
                <div><div class="label">Firmware</div><div class="value">{{ $audit->firmware_version ?? '-' }}</div></div>
                <div><div class="label">Software</div><div class="value">{{ $audit->software_version ?? '-' }}</div></div>
                <div><div class="label">OS / Platform</div><div class="value">{{ $audit->os_version ?? '-' }}</div></div>
                <div><div class="label">MAC Address</div><div class="value">{{ $audit->mac_address_observed ?? '-' }}</div></div>
                <div><div class="label">Hostname</div><div class="value">{{ $audit->hostname_observed ?? '-' }}</div></div>
                <div><div class="label">Source IP</div><div class="value">{{ $audit->source_ip_observed ?? '-' }}</div></div>
            </div>
        </div>

        <div class="card">
            <h2>Time / Clock Check</h2>

            <div class="details">
                <div><div class="label">System Date/Time</div><div class="value">{{ $audit->system_datetime ? $audit->system_datetime->format('M j, Y g:i A') : '-' }}</div></div>
                <div><div class="label">System Time Zone</div><div class="value">{{ $audit->system_time_zone ?? '-' }}</div></div>
                <div><div class="label">NTP Enabled</div><div class="value">{{ $audit->ntp_enabled_label }}</div></div>
            </div>

            <h3>Time Drift Notes</h3>
            <div class="box">{{ $audit->time_drift_notes ?? 'No time drift notes entered.' }}</div>
        </div>
    @endif

    @if($audit->isLocked())
    <div class="card">
        <h2>Storage / Retention</h2>

        @if($audit->isLocked())
            <div class="details">
                <div><div class="label">Total Storage</div><div class="value">{{ $audit->total_storage_amount ? $audit->total_storage_amount . ' ' . ($audit->total_storage_unit ?? '') : '-' }}</div></div>
                <div><div class="label">Used Storage</div><div class="value">{{ $audit->used_storage ?? '-' }}</div></div>
                <div><div class="label">Storage Status</div><div class="value">{{ $audit->storage_status ? ucwords(str_replace('_', ' ', $audit->storage_status)) : '-' }}</div></div>
                <div><div class="label">Oldest Recording</div><div class="value">{{ $audit->oldest_recording_at ? $audit->oldest_recording_at->format('M j, Y g:i A') : '-' }}</div></div>
                <div><div class="label">Oldest Recording Verified</div><div class="value">{{ $audit->oldest_recording_verified_label }}</div></div>
                <div><div class="label">Estimated Retention</div><div class="value">{{ $audit->estimated_retention_days ? $audit->estimated_retention_days . ' days' : '-' }}</div></div>
                <div><div class="label">Recording Mode</div><div class="value">{{ $audit->recording_mode ? ucwords(str_replace('_', ' ', $audit->recording_mode)) : '-' }}</div></div>
                <div><div class="label">Export Test Performed</div><div class="value">{{ $audit->export_test_performed_label }}</div></div>
                <div><div class="label">Export Test Status</div><div class="value">{{ $audit->export_test_status ? ucwords(str_replace('_', ' ', $audit->export_test_status)) : '-' }}</div></div>
            </div>

            <h3>Export Test Notes</h3>
            <div class="box">{{ $audit->export_test_notes ?? 'No export test notes entered.' }}</div>

            <h3>Retention Notes</h3>
            <div class="box">{{ $audit->retention_notes ?? 'No retention notes entered.' }}</div>
        @endif

        <h3>Upload Oldest / Export Test Clip</h3>
        <form method="POST" action="{{ route('video-source-audits.attachments.store', $audit) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="attachment_type" value="oldest_clip">

            <div class="field">
                <label for="oldest_clip_file">Clip File</label>
                <input type="file" name="file" id="oldest_clip_file" required>
                @error('file') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label for="oldest_clip_notes">Clip Notes</label>
                <textarea name="notes" id="oldest_clip_notes" placeholder="Example: Exported oldest available clip, 30 seconds, front door camera.">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn">Upload Clip</button>
        </form>
    </div>

    @if($audit->isLocked())
        <div class="card">
            <h2>Camera Inventory / Views</h2>

            <div class="details">
                <div><div class="label">Total Cameras</div><div class="value">{{ $audit->total_camera_count ?? '-' }}</div></div>
                <div><div class="label">Offline Cameras</div><div class="value">{{ $audit->offline_camera_count ?? '-' }}</div></div>
            </div>

            <h3>Camera View Notes</h3>
            <div class="box">{{ $audit->camera_view_notes ?? 'No camera view notes entered.' }}</div>
        </div>

        <div class="card">
            <h2>User / Access / Security</h2>

            <div class="details">
                <div><div class="label">Admin Users</div><div class="value">{{ $audit->admin_user_count ?? '-' }}</div></div>
                <div><div class="label">Standard Users</div><div class="value">{{ $audit->standard_user_count ?? '-' }}</div></div>
            </div>

            <h3>Last Login Notes</h3>
            <div class="box">{{ $audit->last_login_notes ?? 'No last login notes entered.' }}</div>

            <h3>Failed Login Notes</h3>
            <div class="box">{{ $audit->failed_login_notes ?? 'No failed login notes entered.' }}</div>

            <h3>Unusual Activity Notes</h3>
            <div class="box">{{ $audit->unusual_activity_notes ?? 'No unusual activity notes entered.' }}</div>

            <h3>Security Notes</h3>
            <div class="box">{{ $audit->security_notes ?? 'No security notes entered.' }}</div>
        </div>
    @endif

    <div class="card">
        <h2>Logs</h2>

        @if($audit->isLocked())
            <div class="details">
                <div><div class="label">Logs Reviewed</div><div class="value">{{ $audit->logs_reviewed_label }}</div></div>
                <div><div class="label">Log Review Window</div><div class="value">{{ $audit->log_review_window ?? '-' }}</div></div>
            </div>

            <h3>Log Notes</h3>
            <div class="box">{{ $audit->log_notes ?? 'No log notes entered.' }}</div>
        @endif

        <h3>Upload Log File</h3>
        <form method="POST" action="{{ route('video-source-audits.attachments.store', $audit) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="attachment_type" value="log_file">

            <div class="field">
                <label for="log_file">Log File</label>
                <input type="file" name="file" id="log_file" required>
                @error('file') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label for="log_file_notes">Log File Notes</label>
                <textarea name="notes" id="log_file_notes" placeholder="Example: NVR event log export covering last 24 hours.">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn">Upload Log</button>
        </form>
    </div>

    @endif

    <div class="card">
        <h2>Audit Attachments</h2>

        <p style="margin-top: -8px;">
            Supporting files are hashed and become part of the trust chain.
        </p>

        <form method="POST" action="{{ route('video-source-audits.attachments.store', $audit) }}" enctype="multipart/form-data">
            @csrf

            <div class="grid">
                <div class="field">
                    <label for="attachment_type">Attachment Type</label>
                    <select name="attachment_type" id="attachment_type" required>
                        <option value="system_screenshot">System Status Screenshot</option>
                        <option value="camera_list">Camera List Export</option>
                        <option value="camera_view_screenshot">Camera View Screenshot</option>
                        <option value="report_file">Report File</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="field">
                    <label for="file">Attachment File</label>
                    <input type="file" name="file" id="file" required>
                    @error('file') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="field">
                <label for="attachment_notes">Attachment Notes</label>
                <textarea name="notes" id="attachment_notes">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn">Upload Attachment</button>
        </form>

        <hr>

        @if($audit->attachments()->count())
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>File</th>
                        <th>Size</th>
                        <th>Uploaded By</th>
                        <th>Uploaded At</th>
                        <th>IP</th>
                        <th>SHA-256</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($audit->attachments as $attachment)
                        <tr>
                            <td>{{ $attachment->attachment_type_label }}</td>

                            <td>
                                <a href="{{ $attachment->url }}" target="_blank">
                                    {{ $attachment->original_filename }}
                                </a>

                                @if($attachment->notes)
                                    <div style="font-size: 13px; color: #4b5563; margin-top: 5px;">
                                        {{ $attachment->notes }}
                                    </div>
                                @endif
                            </td>

                            <td>{{ $attachment->human_file_size }}</td>
                            <td>{{ $attachment->uploader_display_name }}</td>
                            <td>{{ $attachment->created_at ? $attachment->created_at->format('M j, Y g:i A') : '-' }}</td>
                            <td>{{ $attachment->ip_address ?? '-' }}</td>

                            <td style="font-family: monospace; font-size: 12px; word-break: break-all;">
                                {{ $attachment->sha256_hash ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">
                <h3>No audit attachments yet</h3>
                <p>Upload a log file, oldest clip, export test clip, screenshot, or supporting file.</p>
            </div>
        @endif
    </div>

    <div class="card">
        <h2>Audit Addendums</h2>

        <p style="margin-top: -8px;">
            Add append-only notes, corrections, legal updates, customer confirmations, or follow-up observations.
        </p>

        <form method="POST" action="{{ route('video-source-audits.addendums.store', $audit) }}">
            @csrf

            <div class="field">
                <label for="addendum_type">Addendum Type</label>
                <select name="addendum_type" id="addendum_type" required>
                    <option value="note">General Note</option>
                    <option value="correction">Correction</option>
                    <option value="legal_update">Legal Update</option>
                    <option value="customer_update">Customer Update</option>
                    <option value="follow_up">Follow-up Observation</option>
                    <option value="maintenance_update">Maintenance Update</option>
                </select>
            </div>

            <div class="field">
                <label for="body">Addendum Note</label>
                <textarea name="body" id="body" required placeholder="Example: Customer confirmed this audit is related to a pending slip-and-fall claim."></textarea>
                @error('body') <div class="error">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn">Add Addendum</button>
        </form>

        <hr>

        @if($audit->addendums()->count())
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Note</th>
                        <th>Added By</th>
                        <th>Added At</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($audit->addendums as $addendum)
                        <tr>
                            <td>{{ $addendum->addendum_type_label }}</td>
                            <td>{{ $addendum->body }}</td>
                            <td>{{ $addendum->author_display_name }}</td>
                            <td>{{ $addendum->created_at ? $addendum->created_at->format('M j, Y g:i A') : '-' }}</td>
                            <td>{{ $addendum->ip_address ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">
                <h3>No addendums yet</h3>
                <p>Add a note when something needs to be appended without changing the original audit.</p>
            </div>
        @endif
    </div>

    @if($audit->isLocked())
        <div class="card">
            <h2>Summary / Next Steps</h2>

            <h3>Overall Notes</h3>
            <div class="box">{{ $audit->overall_notes ?? 'No overall notes entered.' }}</div>

            <h3>Recommended Actions</h3>
            <div class="box">{{ $audit->recommended_actions ?? 'No recommended actions entered.' }}</div>
        </div>
    @endif
@endsection
