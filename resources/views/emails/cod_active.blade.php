<div style="max-width:680px; min-width:320px; margin:0 auto; font-family:Helvetica, sans-serif; padding: 15px 0;background: #F0F3F6">
    <div style="background: #fff;max-width:600px;margin: 0 auto">
        <div style="padding: 15px">
            <p style="text-align: center">
                <img src="http://i.imgur.com/dgBPbri.png">
            </p>
            <p style="text-align: center;font-size: 14px"><b>Mạng xã hội Đào tạo kỹ năng Hướng nghiệp, tìm việc làm</b></p>
            <table style="border: 0;border-spacing:0;text-indent:0;border-collapse:collapse; font-size:13px;width: 100%;margin-bottom: 15px;">
                <tr>
                    <td colspan="3"><b>Xác nhận</b></td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;font-size: 14px;width: 15%">Học viên</td>
                    <td style="width: 15%">:</td>
                    <td style="padding: 5px 0;font-size: 14px;width: width: 70%">{{ $contact->contact_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;font-size: 14px;width: 15%">Địa chỉ</td>
                    <td>:</td>
                    <td style="padding: 5px 0;font-size: 14px;width: 15%">{{ $contact->contact_address }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;font-size: 14px;width: 15%">Khóa học</td>
                    <td>:</td>
                    <td style="padding: 5px 0;font-size: 14px;">{{ $course->cou_title }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;font-size: 14px;width: 15%">Học phí</td>
                    <td>:</td>
                    <td style="padding: 5px 0;font-size: 14px;">{{ number_format($course->cou_price) . " VNĐ" }}</td>
                </tr>
            </table>
            <hr>
            <p style="font-size: 14px;line-height: 18px;"><b>Myedu</b> Mạng xã hội Đào tạo kỹ năng Hướng nghiệp, tìm việc làm trân trọng cảm ơn quý khách hàng đã tham gia học tập và thanh toán qua hình thức COD (Giao hàng tận nơi). </p>
            <p style="text-align: center;text-transform: uppercase;font-size: 14px;font-weight: bold">Thông tin khóa học và mã kích hoạt</p>
            <table style="border: 0;border-spacing:0;text-indent:0;border-collapse:collapse; font-size:13px;width: 100%;margin-bottom: 15px;">
                <tr>
                    <td style="padding: 5px 0;font-size: 14px;width: 15%">Khóa học</td>
                    <td style="width: 15%">:</td>
                    <td style="padding: 5px 0;font-size: 14px;width: 70%">{{ $course->cou_title }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;font-size: 14px;width: 15%">Giảng viên</td>
                    <td>:</td>
                    <td style="padding: 5px 0;font-size: 14px;width: 15%">{{ $teacher }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;font-size: 14px;width: 15%">Mã kích họat</td>
                    <td style="width: 15%">:</td>
                    <td style="padding: 5px 0;font-size: 14px;width: 70%"><b>{{ $contact->code }}</b></td>
                </tr>
            </table>
            <hr>
            <p style="font-size: 14px">Quý học viên luôn được hưởng các lợi ích của Myedu</p>
            <div style="width: 100%;overflow: hidden">
                <div style="width: 30%;float: left;margin-right: 28px;">
                    <p>
                        <img style="max-width: 100%;height: 165px" src="http://i.imgur.com/sr0MqfZ.png">
                    </p>
                    <p style="font-size: 14px">Giáo trình luôn cập nhật theo thị trường tuyển dụng</p>
                </div>
                <div style="width: 30%;float: left;margin-right: 28px;">
                    <p>
                        <img style="max-width: 100%;height: 165px" src="http://i.imgur.com/O7Dj1VW.png">
                    </p>
                    <p style="font-size: 14px">Trọn đời sở hữu học liệu, chủ động không gian, thời gian</p>
                </div>
                <div style="width: 30%;float: left">
                    <p>
                        <img style="max-width: 100%;height: 165px" src="http://i.imgur.com/rsVKbBr.png">
                    </p>
                    <p style="font-size: 14px">Hỗ trợ đến khi tìm kiếm được việc và thành thạo kỹ năng</p>
                </div>
            </div>
            <hr>
            <div style="width: 100%;overflow: hidden">
                <p style="float: left;font-size: 14px">
                    Email: <a href="mailto:support@myedu.com.vn" target="_blank">support@myedu.com.vn</a>
                </p>
                <p style="float: right;font-size: 14px">
                    Hotline: <a href="tel:0462939998" target="_blank">04 6293 9998</a>
                </p>
            </div>
        </div>
    </div>
</div>