@include('admin.blocks.header')
<div class="container body">
    <div class="main_container">
        @include('admin.blocks.sidebar')

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">

                <div class="page-title">
                    <div class="title_left">
                        <h3>Liên hệ</h3>
                    </div>

                </div>

                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Tại đây, bạn có thể xem và quản lý các thông tin liên lạc từ khách hàng, trả lời câu
                                    hỏi, <br> và theo dõi các trao đổi để cải thiện dịch vụ.</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="col-sm-3 mail_list_column">
                                        <label for="" class="badge bg-green"
                                            style="width: 100%;line-height: 2;font-size: 16px;">Liên hệ khách
                                            hàng</label>
                                        @foreach ($contacts as $contact)
                                            <a href="javascript:void(0)" class="contact-item"
                                                data-name="{{ $contact->name }}" data-email="{{ $contact->email }}"
                                                data-message="{{ $contact->message }}" data-contactid="{{ $contact->contactId }}">
                                                <div class="mail_list">
                                                    <div class="left">
                                                        <i class="fa fa-circle"></i> <i class="fa fa-edit"></i>
                                                    </div>
                                                    <div class="right">
                                                        <h3>{{ $contact->name }}
                                                            <small>{{ $contact->phoneNumber ?? 'N/A' }}</small>
                                                        </h3>
                                                        <p class="text-contact-truncate">{{ $contact->message }}</p>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                    <!-- /MAIL LIST -->

                                    <!-- CONTENT MAIL -->
                                    <div class="col-sm-9 mail_view">
                                        <div class="inbox-body">
                                            <div class="sender-info" style="border-bottom: 1px solid #ddd">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <strong></strong>
                                                        <span></span> to
                                                        <b>me</b>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="view-mail">
                                                <p></p>
                                                <div class="btn-group">
                                                    <button id="btn-reply-open" class="btn btn-sm btn-primary"
                                                        type="button"><i class="fa fa-reply"></i> Reply</button>

                                                    <button class="btn btn-sm btn-default" type="button"
                                                        data-placement="top" data-toggle="tooltip"
                                                        data-original-title="Trash"><i
                                                            class="fa fa-trash-o"></i></button>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- /CONTENT MAIL -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /page content -->
        </div>
    </div>

    <!-- compose reply panel -->
    <div id="reply-compose-panel" style="display:none; position:fixed; bottom:0; right:0; width:40%; min-width:360px;
        background:#fff; border:1px solid #D9DEE4; border-right:0; border-bottom:0;
        border-top-left-radius:5px; z-index:10000; box-shadow:-2px -2px 8px rgba(0,0,0,0.15);">
        <div style="padding:8px 12px; background:#169F85; color:#fff; border-top-left-radius:5px; display:flex; justify-content:space-between; align-items:center;">
            <span><i class="fa fa-reply"></i> Phản hồi liên hệ</span>
            <button type="button" id="btn-reply-close" style="background:none; border:none; color:#fff; font-size:18px; cursor:pointer; line-height:1;">×</button>
        </div>

        <div style="padding:12px;">
            <textarea id="editor-contact" rows="8"
                placeholder="Nhập nội dung phản hồi cho khách hàng..."
                style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; font-size:14px; resize:vertical; font-family:Arial,sans-serif; display:block;"></textarea>
        </div>

        <div style="padding:8px 12px; border-top:1px solid #eee;">
            <button class="send-reply-contact btn btn-sm btn-success" type="button"
                data-url="{{ route('admin.reply-contact') }}">
                <i class="fa fa-paper-plane"></i> Gửi phản hồi
            </button>
            <button type="button" id="btn-reply-close2" class="btn btn-sm btn-default" style="margin-left:6px;">Hủy</button>
        </div>
    </div>
    <!-- /compose reply panel -->

    @include('admin.blocks.footer')
