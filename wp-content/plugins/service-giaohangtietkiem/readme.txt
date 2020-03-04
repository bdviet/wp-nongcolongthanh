=== Giaohangtietkiem WooCommerce ===
Contributors: ghtk
Plugin URI: https://ghtk.vn/
Donate link: https://ghtk.vn/
Tags: woocommerce, shipping, vietnam, checkout, shop, ghtk, giao hang tiet kiem
Requires at least: 4.0
Tested up to: 5.0.1
Stable tag: 2.0.6
License: MIT
License URI: http://opensource.org/licenses/MIT
Requires PHP: 5.6

== Description ==

* Plugin hỗ trợ toàn diện giao vận tại Việt Nam cho WooCommerce. Khách hàng chủ động chọn đơn vị giao vận và các gói giao vận ( Nhanh, Chuẩn, Tiết Kiệm ) tuỳ theo hầu bao của mình, việc này tạo sự tin tưởng cho người mua vì công khai chi phí ship giúp tăng tỉ lệ đặt hàng cho quản trị shop. Quản trị shop dễ dàng đăng vận đơn lên các đơn vị giao vận tuỳ theo lựa chọn của khách hàng khi đặt hàng chỉ với 1 Click, cùng với đó là tra cứu trạng thái vận đơn ngay từ trang quản trị.
* Plugin sử dụng API được hỗ trợ từ đơn vị vận chuyển Giaohangtietkiem
* Doc API: https://docs.giaohangtietkiem.vn
* Môi trường sanbox: https://dev.ghtk.vn
* Môi trường product: https://services.giaohangtietkiem.vn

=== HỖ TRỢ CÁC ĐƠN VỊ GIAO VẬN ===
* Giao Hàng Tiết Kiệm

=== CHỨC NĂNG CHÍNH ===
* Tính toán phí ship ngay khi đặt hàng.
* Khách hàng chủ động chọn gói ship phù hợp.
* Phí ship được tính toán trực tiếp từ GHTK, khách hàng biết rõ phí ship khi đặt hàng làm tăng độ tin tưởng của khách hàng đi đặt hàng. Shopper không cần tra phí mỗi khi khách hàng hỏi ship về A B C thì mất bao nhiêu phí.
* Đăng vận đơn lên GHTK chỉ với 1 click.
* Tối ưu hoá form checkout.

=== Không hiện phí ship sau khi active plugin ? ===
Mặc tính chức năng này được disable sau khi active, các bạn cần vào WooCommerce -> Setting -> Shipping -> Giao Hàng Tiết Kiệm để nhập thông tin người gửi và kích hoạt tính phí. Tối thiểu phải có thông tin người gửi, số điện thoại, tỉnh thành, quận huyện và token. đối với GHTK, Token chỉ có hiệu lực sau khi tài khoản đó được nhân viên gọi điện xác nhận tài khoản.

=== Cài đặt trọng lượng sản phẩm như thế nào ? ===
Bạn phải chọn đơn vị trọng lượng trong Woo là KG, đối với sản phẩm dưới 1KG, ví dụ 200 Gram thì nhập 0.2.

=== Screenshots ===

1. Kích hoạt và cài đặt thông tin người gửi.
2. Tra cứu trạng thái vận đơn khi xem order.
3. Các field và tính toán chi phí GHTK.
4. Đăng đơn lên GHTK
5. In đơn hàng GHTK
6. Hủy đơn hàng trên GHTK

== CHANGELOG ==

= 1.0.0 (01/01/2020) =
* Phiên bản đầu.
== Upgrade Notice ==

= 1.0 =

== Frequently Asked Questions ==
= Hỗ trợ đơn vị vận chuyển GHKT=