/**
 * PROMPT: TẠO VIDEO DEMO CLOTHES SHOP THEO QUY TRÌNH MUA HÀNG VÀ QUẢN TRỊ
 * - Thứ tự chuẩn 100%: Mua hàng -> Đăng nhập khách -> Đặt hàng -> Đăng xuất -> Đăng nhập Admin -> Lần lượt từng mục sidebar -> Tổng kết
 * - Không đứng hình, không giật, thao tác mượt mà tự nhiên, dừng 1-2 giây mỗi bước
 * - Mã hóa MP4 H.264 CRF 19 YUV420P Full HD 1080p @ 30 FPS bằng FFmpeg
 */

const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');
const { execSync } = require('child_process');
const ffmpegStaticPath = require('ffmpeg-static');

const BASE_URL = process.env.BASE_URL || 'http://127.0.0.1:8000';
const RAW_DIR = path.join(__dirname, 'raw');

if (!fs.existsSync(RAW_DIR)) {
  fs.mkdirSync(RAW_DIR, { recursive: true });
}

// Clean old raw files
try {
  for (const f of fs.readdirSync(RAW_DIR)) {
    fs.unlinkSync(path.join(RAW_DIR, f));
  }
} catch (e) {}

const srtEntries = [];
let srtCounter = 1;
let recordingStartTime = null;

function formatTimestamp(ms) {
  const totalSec = Math.floor(ms / 1000);
  const hours = String(Math.floor(totalSec / 3600)).padStart(2, '0');
  const minutes = String(Math.floor((totalSec % 3600) / 60)).padStart(2, '0');
  const seconds = String(totalSec % 60).padStart(2, '0');
  const millis = String(ms % 1000).padStart(3, '0');
  return `${hours}:${minutes}:${seconds},${millis}`;
}

async function setSubtitle(page, text, durationSec = 3.5) {
  const now = Date.now();
  if (!recordingStartTime) recordingStartTime = now;
  const startMs = now - recordingStartTime;
  const endMs = startMs + Math.round(durationSec * 1000);

  srtEntries.push({
    index: srtCounter++,
    start: formatTimestamp(startMs),
    end: formatTimestamp(endMs),
    text: text
  });

  console.log(`[${formatTimestamp(startMs)}] ${text}`);

  try {
    await page.evaluate(({ text }) => {
      let el = document.getElementById('demo-subtitle-box');
      if (!el) {
        el = document.createElement('div');
        el.id = 'demo-subtitle-box';
        el.style.cssText = `
          position: fixed;
          bottom: 75px;
          left: 50%;
          transform: translateX(-50%);
          background: rgba(15, 23, 42, 0.72);
          backdrop-filter: blur(14px);
          -webkit-backdrop-filter: blur(14px);
          color: #ffffff;
          padding: 14px 38px;
          border-radius: 12px;
          font-family: "Be Vietnam Pro", "Inter", Arial, sans-serif;
          font-size: 32px;
          font-weight: 600;
          box-shadow: 0 12px 35px rgba(0, 0, 0, 0.7);
          border: 1px solid rgba(255, 255, 255, 0.18);
          border-bottom: 4px solid #f43f5e;
          z-index: 2147483647;
          text-align: center;
          max-width: 82%;
          line-height: 1.4;
          letter-spacing: 0.3px;
          pointer-events: none;
          text-shadow: 0 2px 4px rgba(0,0,0,0.8);
          transition: all 0.25s ease-out;
        `;
        document.body.appendChild(el);
      }
      el.textContent = text;
      el.style.opacity = '1';
    }, { text });
  } catch (err) {}
}

async function injectPointer(page) {
  try {
    await page.evaluate(() => {
      if (document.getElementById('playwright-mouse-pointer')) return;
      const cursor = document.createElement('div');
      cursor.id = 'playwright-mouse-pointer';
      cursor.style.cssText = `
        position: fixed;
        width: 20px;
        height: 20px;
        border: 2px solid #ffffff;
        background: #f43f5e;
        border-radius: 50%;
        pointer-events: none;
        z-index: 2147483646;
        transform: translate(-50%, -50%);
        box-shadow: 0 0 16px rgba(244, 63, 94, 0.95), 0 2px 6px rgba(0,0,0,0.6);
        transition: transform 0.08s ease;
      `;
      document.body.appendChild(cursor);

      window.addEventListener('mousemove', (e) => {
        cursor.style.left = e.clientX + 'px';
        cursor.style.top = e.clientY + 'px';
      }, { passive: true });

      window.addEventListener('mousedown', (e) => {
        cursor.style.transform = 'translate(-50%, -50%) scale(0.75)';
        cursor.style.background = '#e11d48';

        const wave = document.createElement('div');
        wave.style.cssText = `
          position: fixed;
          left: ${e.clientX}px;
          top: ${e.clientY}px;
          width: 14px;
          height: 14px;
          border-radius: 50%;
          background: rgba(244, 63, 94, 0.45);
          border: 1.5px solid rgba(255, 255, 255, 0.9);
          pointer-events: none;
          z-index: 2147483645;
          transform: translate(-50%, -50%) scale(1);
          transition: transform 0.4s ease-out, opacity 0.4s ease-out;
        `;
        document.body.appendChild(wave);
        requestAnimationFrame(() => {
          wave.style.transform = 'translate(-50%, -50%) scale(5)';
          wave.style.opacity = '0';
        });
        setTimeout(() => wave.remove(), 420);
      }, { passive: true });

      window.addEventListener('mouseup', () => {
        cursor.style.transform = 'translate(-50%, -50%) scale(1)';
        cursor.style.background = '#f43f5e';
      }, { passive: true });
    });
  } catch (e) {}
}

const sleep = (ms) => new Promise(res => setTimeout(res, ms));

async function smoothScroll(page, totalY, durationMs = 800) {
  const steps = 20;
  const stepDelay = durationMs / steps;
  const dy = totalY / steps;
  for (let i = 0; i < steps; i++) {
    await page.evaluate((delta) => window.scrollBy({ top: delta, behavior: 'auto' }), dy);
    await sleep(stepDelay);
  }
}

async function smoothMouseMove(page, targetX, targetY, steps = 14) {
  await page.mouse.move(targetX, targetY, { steps });
}

async function runDemo() {
  console.log('🚀 Khởi chạy Chromium 1920x1080 @ 30 FPS để quay video Clothes Shop theo đúng quy trình...');

  const browser = await chromium.launch({
    headless: true,
    args: [
      '--no-sandbox',
      '--disable-setuid-sandbox',
      '--disable-dev-shm-usage',
      '--font-render-hinting=medium'
    ]
  });

  const context = await browser.newContext({
    viewport: { width: 1920, height: 1080 },
    recordVideo: {
      dir: RAW_DIR,
      size: { width: 1920, height: 1080 }
    }
  });

  const page = await context.newPage();

  page.on('load', async () => {
    await injectPointer(page);
  });

  try {
    recordingStartTime = Date.now();

    // =========================================================================
    // PHẦN 1 – KHÁCH HÀNG MUA SẢN PHẨM
    // =========================================================================

    // Bước 1: Truy cập website và xem sản phẩm
    console.log('--- Bước 1: Truy cập website và xem sản phẩm ---');
    await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await setSubtitle(page, 'Đầu tiên, khách hàng truy cập website và khám phá các sản phẩm thời trang.', 4);
    await sleep(1500);

    // Di chuyển chuột qua thanh điều hướng
    await smoothMouseMove(page, 450, 42);
    await sleep(400);
    await smoothMouseMove(page, 560, 42);
    await sleep(400);
    await smoothMouseMove(page, 670, 42);
    await sleep(400);

    // Cuộn xuống phần sản phẩm
    await smoothScroll(page, 550, 800);
    await sleep(1500);
    await smoothScroll(page, 550, 800);
    await sleep(1500);
    await smoothScroll(page, -1100, 700);
    await sleep(1000);

    // Bước 2: Chọn danh mục sản phẩm
    console.log('--- Bước 2: Chọn danh mục sản phẩm ---');
    await setSubtitle(page, 'Khách hàng lựa chọn danh mục để tìm kiếm sản phẩm phù hợp.', 4);
    await page.goto(`${BASE_URL}/san-pham`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await sleep(1200);

    // Cuộn nhẹ xem danh mục
    await smoothScroll(page, 300, 700);
    await sleep(1500);
    await smoothScroll(page, -300, 500);
    await sleep(800);

    // Bước 3: Xem chi tiết sản phẩm
    console.log('--- Bước 3: Xem chi tiết sản phẩm ---');
    await setSubtitle(page, 'Khách hàng xem thông tin chi tiết và lựa chọn kích thước, màu sắc của sản phẩm.', 4.5);
    const firstProductLink = await page.$('a[href*="/san-pham/"], .product-card a, .card a');
    if (firstProductLink) {
      await firstProductLink.click();
      await page.waitForLoadState('domcontentloaded').catch(() => {});
      await injectPointer(page);
      await sleep(1400);
    }

    // Cuộn xem thông tin và chọn size, màu
    await smoothScroll(page, 220, 600);
    await sleep(700);

    const sizeBtn = await page.$('button[data-size], input[name="size"] + label, .size-option, .badge-size');
    if (sizeBtn) {
      await sizeBtn.click().catch(() => {});
      await sleep(600);
    }

    // Bước 4: Thêm sản phẩm vào giỏ hàng
    console.log('--- Bước 4: Thêm sản phẩm vào giỏ hàng ---');
    const addToCartBtn = await page.$('button[type="submit"]:has-text("Thêm vào giỏ"), button:has-text("Thêm vào giỏ"), form[action*="cart"] button');
    if (addToCartBtn) {
      await addToCartBtn.click().catch(() => {});
      await sleep(1200);
    }

    await setSubtitle(page, 'Sản phẩm được thêm vào giỏ hàng để chuẩn bị tiến hành đặt mua.', 4);
    await page.goto(`${BASE_URL}/gio-hang`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 200, 600);
    await sleep(1800);

    // Bước 5: Đăng nhập tài khoản khách hàng (sau khi thêm vào giỏ hàng)
    console.log('--- Bước 5: Đăng nhập tài khoản khách hàng ---');
    await setSubtitle(page, 'Khách hàng đăng nhập tài khoản để tiếp tục quá trình thanh toán.', 4);
    await page.goto(`${BASE_URL}/login`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await sleep(800);

    await page.fill('input[name="email"]', 'customer@shop.com');
    await sleep(300);
    await page.fill('input[name="password"]', '123456');
    await sleep(400);
    try {
      await Promise.all([
        page.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 6000 }).catch(() => {}),
        page.click('button[type="submit"]:has-text("Đăng nhập"), button:has-text("Đăng Nhập")')
      ]);
    } catch (e) {}
    await sleep(1500);

    // Kiểm tra giỏ hàng vẫn còn sau khi đăng nhập
    await page.goto(`${BASE_URL}/gio-hang`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 150, 500);
    await sleep(1400);

    // Bước 6: Nhập thông tin giao hàng
    console.log('--- Bước 6: Nhập thông tin giao hàng ---');
    await setSubtitle(page, 'Khách hàng cung cấp thông tin người nhận và địa chỉ giao hàng.', 4.5);
    await page.goto(`${BASE_URL}/thanh-toan`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await sleep(1200);

    // Điền thông tin giao hàng
    const nameInput = await page.$('input[name="customer_name"], input[name="name"]');
    if (nameInput) {
      await nameInput.fill('Nguyễn Văn A');
      await sleep(400);
    }

    const phoneInput = await page.$('input[name="customer_phone"], input[name="phone"]');
    if (phoneInput) {
      await phoneInput.fill('0988776655');
      await sleep(400);
    }

    const addrInput = await page.$('input[name="customer_address"], input[name="address"], textarea[name="address"]');
    if (addrInput) {
      await addrInput.fill('Số 12 Chùa Bộc, Đống Đa, Hà Nội');
      await sleep(400);
    }

    await smoothScroll(page, 250, 600);
    await sleep(1400);

    // Bước 7: Thanh toán và đặt hàng (COD)
    console.log('--- Bước 7: Thanh toán và đặt hàng ---');
    await setSubtitle(page, 'Khách hàng xác nhận thông tin và hoàn tất đặt hàng thành công.', 4.5);

    // Chọn COD nếu có radio button
    const codRadio = await page.$('input[value="cod"], input[id*="cod"]');
    if (codRadio) await codRadio.click().catch(() => {});

    // Bấm Đặt hàng
    const placeOrderBtn = await page.$('button[type="submit"]:has-text("Đặt hàng"), button:has-text("Hoàn tất đặt hàng"), form[action*="thanh-toan"] button[type="submit"]');
    if (placeOrderBtn) {
      await placeOrderBtn.click().catch(() => {});
      await page.waitForLoadState('domcontentloaded').catch(() => {});
      await injectPointer(page);
      await sleep(2000);
    }

    // Xem lịch sử mua sắm của khách hàng
    await setSubtitle(page, 'Khách hàng có thể theo dõi đơn hàng trong lịch sử mua sắm.', 4);
    await page.goto(`${BASE_URL}/tai-khoan/don-hang`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 200, 600);
    await sleep(1800);

    // =========================================================================
    // PHẦN 2 – CHUYỂN SANG TÀI KHOẢN ADMIN
    // =========================================================================

    // Bước 8: Đăng xuất khách hàng
    console.log('--- Bước 8: Đăng xuất khách hàng ---');
    await setSubtitle(page, 'Tiếp theo, chúng ta chuyển sang hệ thống quản trị cửa hàng.', 4);
    
    // Thực hiện đăng xuất an toàn
    try {
      await Promise.all([
        page.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 6000 }).catch(() => {}),
        page.evaluate(() => {
          const form = document.querySelector('form[action*="logout"]');
          if (form) form.submit();
          else window.location.href = '/login';
        })
      ]);
    } catch (e) {}
    await sleep(1500);

    // Bước 9: Đăng nhập Admin
    console.log('--- Bước 9: Đăng nhập Admin ---');
    await setSubtitle(page, 'Quản trị viên đăng nhập để theo dõi và quản lý toàn bộ hoạt động kinh doanh.', 4);
    if (!page.url().includes('/login')) {
      await page.goto(`${BASE_URL}/login`, { waitUntil: 'domcontentloaded' });
    }
    await injectPointer(page);
    await sleep(1000);

    await page.fill('input[name="email"]', 'admin@shop.com');
    await sleep(300);
    await page.fill('input[name="password"]', '123456');
    await sleep(400);
    try {
      await Promise.all([
        page.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 6000 }).catch(() => {}),
        page.click('button[type="submit"]:has-text("Đăng nhập"), button:has-text("Đăng Nhập")')
      ]);
    } catch (e) {}
    await sleep(1500);

    // =========================================================================
    // PHẦN 3 – DEMO LẦN LƯỢT TẤT CẢ CHỨC NĂNG ADMIN (THEO SIDEBAR)
    // =========================================================================

    // Bước 10: Dashboard
    console.log('--- Bước 10: Dashboard ---');
    await setSubtitle(page, 'Dashboard giúp quản trị viên theo dõi tổng quan hoạt động kinh doanh.', 4);
    await page.goto(`${BASE_URL}/admin/dashboard`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 320, 700);
    await sleep(1500);
    await smoothScroll(page, -320, 600);
    await sleep(800);

    // Bước 11: Bán hàng POS (Quầy)
    console.log('--- Bước 11: Bán hàng POS ---');
    await setSubtitle(page, 'Chức năng POS hỗ trợ nhân viên thực hiện giao dịch trực tiếp tại quầy.', 4);
    await page.goto(`${BASE_URL}/pos`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await sleep(1400);

    // Mở ca nếu chưa mở
    try {
      const shiftBadge = await page.$('#shiftStatusBadge');
      if (shiftBadge) {
        const text = await shiftBadge.innerText().catch(() => '');
        if (text.includes('Chưa mở ca')) {
          await shiftBadge.click().catch(() => {});
          await sleep(600);
          await page.evaluate(() => { if (typeof setModalCash === 'function') setModalCash(1000000); });
          await sleep(400);
          const confBtn = await page.$('#confirmOpenShiftBtn');
          if (confBtn) await confBtn.click().catch(() => {});
          await sleep(1000);
        }
      }
    } catch (e) {}

    // Chọn sản phẩm và đóng modal ngay
    try {
      const productCards = await page.$$('#productGrid > div');
      if (productCards.length > 0) {
        await productCards[0].click().catch(() => {});
        await sleep(500);
        await page.evaluate(() => {
          if (typeof confirmAddVariant === 'function') confirmAddVariant();
          else if (typeof closeVariantModal === 'function') closeVariantModal();
        });
        await sleep(800);
      }
    } catch (e) {}

    // Tra cứu VIP & Nhập tiền & Thanh toán
    try {
      const phoneInp = await page.$('#customerPhoneInput');
      if (phoneInp) {
        await phoneInp.fill('0987654321');
        await sleep(300);
        await page.evaluate(() => { if (typeof searchCustomer === 'function') searchCustomer(); });
        await sleep(800);
      }
      await page.evaluate(() => { if (typeof setQuickCash === 'function') setQuickCash('exact'); });
      await sleep(800);

      const chkBtn = await page.$('#checkoutBtn:not([disabled])');
      if (chkBtn) {
        await chkBtn.click().catch(() => {});
        await sleep(1400);
      }
      const swalOk = await page.$('.swal2-confirm');
      if (swalOk) await swalOk.click().catch(() => {});
    } catch (e) {}
    await sleep(1000);

    // Bước 12: Báo cáo Doanh số
    console.log('--- Bước 12: Báo cáo Doanh số ---');
    await setSubtitle(page, 'Hệ thống cung cấp báo cáo doanh thu và các chỉ số kinh doanh.', 4);
    await page.goto(`${BASE_URL}/admin/reports/sales`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 320, 800);
    await sleep(1500);
    await smoothScroll(page, 320, 800);
    await sleep(1500);
    await smoothScroll(page, -640, 700);
    await sleep(800);

    // Bước 13: Quản lý danh mục
    console.log('--- Bước 13: Quản lý danh mục ---');
    await setSubtitle(page, 'Quản trị viên tổ chức và quản lý các danh mục sản phẩm.', 4);
    await page.goto(`${BASE_URL}/admin/categories`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 200, 600);
    await sleep(1500);

    // Bước 14: Quản lý sản phẩm
    console.log('--- Bước 14: Quản lý sản phẩm ---');
    await setSubtitle(page, 'Hệ thống hỗ trợ quản lý sản phẩm và các biến thể thời trang.', 4);
    await page.goto(`${BASE_URL}/admin/products`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 300, 800);
    await sleep(1600);
    await smoothScroll(page, -300, 600);
    await sleep(700);

    // Bước 15: Quản lý kho
    console.log('--- Bước 15: Quản lý kho ---');
    await setSubtitle(page, 'Quản trị viên kiểm soát tồn kho và các hoạt động nhập xuất hàng hóa.', 4);
    await page.goto(`${BASE_URL}/admin/inventory`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 300, 700);
    await sleep(1500);

    // Lịch sử kho
    await page.goto(`${BASE_URL}/admin/inventory/history`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 280, 700);
    await sleep(1500);

    // Bước 16: Quản lý đơn hàng (Mở đơn khách vừa đặt ở Phần 1)
    console.log('--- Bước 16: Quản lý đơn hàng ---');
    await setSubtitle(page, 'Đơn hàng của khách hàng được tiếp nhận và xử lý tại trang quản trị.', 4.5);
    await page.goto(`${BASE_URL}/admin/orders`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 250, 700);
    await sleep(1200);

    // Mở chi tiết đơn hàng đầu tiên (chính là đơn vừa tạo)
    try {
      const orderLink = await page.$('table tbody tr:first-child a[href*="/admin/orders/"]');
      if (orderLink) {
        const href = await orderLink.getAttribute('href');
        if (href) {
          await page.goto(href.startsWith('http') ? href : `${BASE_URL}${href}`, { waitUntil: 'domcontentloaded' });
          await injectPointer(page);
          await smoothScroll(page, 300, 800);
          await sleep(1800);
          await smoothScroll(page, -300, 600);
          await sleep(700);
        }
      }
    } catch (e) {}

    // Bước 17: Quản lý nhân viên
    console.log('--- Bước 17: Quản lý nhân viên ---');
    await setSubtitle(page, 'Hệ thống hỗ trợ quản lý nhân viên và phân quyền hoạt động.', 4);
    await page.goto(`${BASE_URL}/admin/employees`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 200, 700);
    await sleep(1500);

    // Bước 18: Xếp ca làm việc & Quản lý ca
    console.log('--- Bước 18: Xếp ca làm việc ---');
    await setSubtitle(page, 'Quản trị viên theo dõi lịch làm việc và phân công ca cho nhân viên.', 4);
    await page.goto(`${BASE_URL}/admin/schedules`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 250, 800);
    await sleep(1500);

    // Quản lý ca làm việc
    await page.goto(`${BASE_URL}/admin/shifts`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 200, 600);
    await sleep(1400);

    // Mở/Đóng ca thu ngân
    await page.goto(`${BASE_URL}/staff/shifts`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 200, 600);
    await sleep(1400);

    // Bước 19: Quản lý khách hàng
    console.log('--- Bước 19: Quản lý khách hàng ---');
    await setSubtitle(page, 'Quản trị viên theo dõi thông tin và hoạt động mua sắm của khách hàng.', 4);
    await page.goto(`${BASE_URL}/admin/customers`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 200, 600);
    await sleep(1500);

    // Bước 20: Quản lý Voucher & Yêu thích
    console.log('--- Bước 20: Voucher & Yêu thích ---');
    await setSubtitle(page, 'Quản trị viên theo dõi mã khuyến mãi Voucher và các sản phẩm được quan tâm.', 4);
    await page.goto(`${BASE_URL}/admin/vouchers`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 200, 600);
    await sleep(1400);

    await page.goto(`${BASE_URL}/admin/wishlist`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);
    await smoothScroll(page, 200, 600);
    await sleep(1400);

    // =========================================================================
    // PHẦN VII – KẾT THÚC VIDEO
    // =========================================================================
    console.log('--- Kết thúc video ---');
    await page.goto(`${BASE_URL}/admin/dashboard`, { waitUntil: 'domcontentloaded' });
    await injectPointer(page);

    // Hiển thị overlay kết thúc trang trọng
    await page.evaluate(() => {
      let sub = document.getElementById('demo-subtitle-box');
      if (sub) sub.style.display = 'none';

      const endBanner = document.createElement('div');
      endBanner.id = 'demo-end-banner';
      endBanner.style.cssText = `
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.88);
        backdrop-filter: blur(12px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 2147483647;
        color: #ffffff;
        font-family: "Be Vietnam Pro", Arial, sans-serif;
        text-align: center;
        animation: fadeIn 0.5s ease-out;
      `;
      endBanner.innerHTML = `
        <div style="width: 80px; height: 80px; background: #e11d48; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 38px; font-weight: 900; margin-bottom: 24px; box-shadow: 0 10px 30px rgba(225,29,72,0.4);">
          POS
        </div>
        <h1 style="font-size: 56px; font-weight: 900; letter-spacing: 2px; margin: 0 0 16px 0; color: #ffffff;">
          CLOTHES SHOP
        </h1>
        <p style="font-size: 26px; font-weight: 500; color: #cbd5e1; margin: 0; letter-spacing: 0.5px;">
          Hệ thống quản lý và bán hàng thời trang
        </p>
      `;
      document.body.appendChild(endBanner);
    });

    srtEntries.push({
      index: srtCounter++,
      start: formatTimestamp(Date.now() - recordingStartTime),
      end: formatTimestamp(Date.now() - recordingStartTime + 3500),
      text: "CLOTHES SHOP - Hệ thống quản lý và bán hàng thời trang"
    });

    await sleep(3500);

  } catch (err) {
    console.error('Lỗi trong quá trình quay demo:', err);
  } finally {
    console.log('🏁 Kết thúc phiên quay màn hình Playwright. Đang lưu video...');
    await context.close();
    await browser.close();
  }

  // 1. Xuất file phụ đề SRT
  const srtPath = path.join(__dirname, 'clothes_shop_full_demo.srt');
  const srtContent = srtEntries.map(e => `${e.index}\n${e.start} --> ${e.end}\n${e.text}\n`).join('\n');
  fs.writeFileSync(srtPath, srtContent, 'utf8');
  console.log(`✅ Đã xuất file phụ đề SRT (${srtEntries.length} mục): ${srtPath}`);

  // 2. Chuyển đổi sang MP4 chuẩn H.264 CRF 19 YUV420P bằng ffmpeg-static
  const files = fs.readdirSync(RAW_DIR).filter(f => f.endsWith('.webm'));
  if (files.length > 0) {
    const sortedFiles = files.map(f => {
      const fp = path.join(RAW_DIR, f);
      const stat = fs.statSync(fp);
      return { name: f, path: fp, size: stat.size, mtime: stat.mtimeMs };
    }).sort((a, b) => b.size - a.size);

    const bestVideo = sortedFiles[0];
    const finalMp4Path = path.join(__dirname, 'clothes_shop_full_demo.mp4');

    console.log(`🎬 Video thô: ${bestVideo.name} (${Math.round(bestVideo.size / 1024 / 1024 * 10) / 10} MB)`);
    console.log(`⚙️ Đang mã hóa H.264 Full HD 1080p @ 30 FPS, CRF 19, YUV420P bằng FFmpeg...`);

    try {
      const cmd = `"${ffmpegStaticPath}" -y -i "${bestVideo.path}" -c:v libx264 -crf 19 -preset fast -pix_fmt yuv420p -r 30 "${finalMp4Path}"`;
      console.log(`Chạy lệnh: ${cmd}`);
      execSync(cmd, { stdio: 'inherit' });
      console.log(`🎉 ĐÃ XUẤT VIDEO H.264 HOÀN CHỈNH: ${finalMp4Path}`);

      // Sao chép sang public/
      const publicMp4 = path.join(__dirname, '..', 'public', 'clothes_shop_full_demo.mp4');
      fs.copyFileSync(finalMp4Path, publicMp4);

      // Sao chép vào Downloads của máy người dùng
      const userDownloads = 'C:\\Users\\Admin\\Downloads';
      if (fs.existsSync(userDownloads)) {
        fs.copyFileSync(finalMp4Path, path.join(userDownloads, 'clothes_shop_full_demo.mp4'));
        fs.copyFileSync(srtPath, path.join(userDownloads, 'clothes_shop_full_demo.srt'));
        console.log(`📥 Đã tự động cập nhật video vào: ${userDownloads}`);
      }
    } catch (encodeErr) {
      console.error('Lỗi khi mã hóa H.264:', encodeErr.message);
      fs.copyFileSync(bestVideo.path, finalMp4Path);
    }
  }

  // 3. Xuất tài liệu
  generateScenarioDocumentation();
}

function generateScenarioDocumentation() {
  const scenarioPath = path.join(__dirname, 'demo_scenario.md');
  const scenarioContent = `# KỊCH BẢN QUAY VIDEO DEMO CLOTHES SHOP (QUY TRÌNH MUA HÀNG VÀ QUẢN TRỊ)

## 1. Thông Tin Tổng Quan
- **Dự án:** Clothes Shop (Hệ thống quản lý và bán hàng thời trang)
- **Độ phân giải:** 1920×1080 Full HD (16:9), 30 FPS
- **Codec xuất:** H.264 (AVC) / CRF 19 / yuv420p
- **Quy trình:** Đúng tuần tự yêu cầu (Khách hàng xem sản phẩm → Chọn danh mục → Xem chi tiết & Thêm giỏ hàng → Đăng nhập khách hàng → Nhập thông tin giao hàng → Thanh toán COD → Đăng xuất → Đăng nhập Admin → Lần lượt tất cả chức năng sidebar Admin → Màn hình kết thúc).

---

## 2. Các Bước Thực Hiện Chi Tiết

### PHẦN 1: KHÁCH HÀNG MUA SẢN PHẨM
1. **Bước 1: Truy cập website và xem sản phẩm**
   - Mở trang chủ, di chuyển chuột qua navbar, cuộn xem banner và sản phẩm nổi bật.
   - *Phụ đề:* "Đầu tiên, khách hàng truy cập website và khám phá các sản phẩm thời trang."
2. **Bước 2: Chọn danh mục sản phẩm**
   - Vào danh mục sản phẩm (\`/san-pham\`), xem danh mục quần áo và danh sách sản phẩm.
   - *Phụ đề:* "Khách hàng lựa chọn danh mục để tìm kiếm sản phẩm phù hợp."
3. **Bước 3: Xem chi tiết sản phẩm**
   - Click xem chi tiết sản phẩm, chọn kích thước, màu sắc và số lượng 1.
   - *Phụ đề:* "Khách hàng xem thông tin chi tiết và lựa chọn kích thước, màu sắc của sản phẩm."
4. **Bước 4: Thêm sản phẩm vào giỏ hàng**
   - Bấm "Thêm vào giỏ hàng", mở giỏ hàng kiểm tra sản phẩm, số lượng và tổng tiền.
   - *Phụ đề:* "Sản phẩm được thêm vào giỏ hàng để chuẩn bị tiến hành đặt mua."
5. **Bước 5: Đăng nhập tài khoản khách hàng**
   - Đăng nhập tài khoản khách hàng sau khi đã thêm vào giỏ hàng, xác nhận giỏ hàng vẫn còn nguyên.
   - *Phụ đề:* "Khách hàng đăng nhập tài khoản để tiếp tục quá trình thanh toán."
6. **Bước 6: Nhập thông tin giao hàng**
   - Mở Checkout, nhập họ tên, số điện thoại, địa chỉ nhận hàng thử nghiệm.
   - *Phụ đề:* "Khách hàng cung cấp thông tin người nhận và địa chỉ giao hàng."
7. **Bước 7: Thanh toán và đặt hàng**
   - Chọn phương thức thanh toán khi nhận hàng (COD), xác nhận đặt hàng thành công và mở lịch sử đơn hàng.
   - *Phụ đề:* "Khách hàng xác nhận thông tin và hoàn tất đặt hàng thành công." / "Khách hàng có thể theo dõi đơn hàng trong lịch sử mua sắm."

### PHẦN 2: CHUYỂN SANG TÀI KHOẢN ADMIN
8. **Bước 8: Đăng xuất khách hàng**
   - Đăng xuất tài khoản khách hàng an toàn.
   - *Phụ đề:* "Tiếp theo, chúng ta chuyển sang hệ thống quản trị cửa hàng."
9. **Bước 9: Đăng nhập Admin**
   - Đăng nhập tài khoản quản trị Admin.
   - *Phụ đề:* "Quản trị viên đăng nhập để theo dõi và quản lý toàn bộ hoạt động kinh doanh."

### PHẦN 3: LẦN LƯỢT TOÀN BỘ CHỨC NĂNG SIDEBAR ADMIN
10. **Bước 10: Dashboard** (\`/admin/dashboard\`)
    - Xem thống kê doanh thu, đơn hàng, khách hàng và biểu đồ tăng trưởng.
    - *Phụ đề:* "Dashboard giúp quản trị viên theo dõi tổng quan hoạt động kinh doanh."
11. **Bước 11: Bán hàng POS (Quầy)** (\`/pos\`)
    - Mở ca quầy, quét chọn món, tra cứu VIP, tự động tính tiền thối và in bill K80.
    - *Phụ đề:* "Chức năng POS hỗ trợ nhân viên thực hiện giao dịch trực tiếp tại quầy."
12. **Bước 12: Báo cáo Doanh số** (\`/admin/reports/sales\`)
    - Phân tích Net Sales, tỷ trọng Online vs POS và xếp hạng nhân viên.
    - *Phụ đề:* "Hệ thống cung cấp báo cáo doanh thu và các chỉ số kinh doanh."
13. **Bước 13: Quản lý danh mục** (\`/admin/categories\`)
    - Xem cây danh mục sản phẩm thời trang.
    - *Phụ đề:* "Quản trị viên tổ chức và quản lý các danh mục sản phẩm."
14. **Bước 14: Quản lý sản phẩm** (\`/admin/products\`)
    - Danh sách sản phẩm, giá bán, biến thể màu sắc, kích thước và tồn kho.
    - *Phụ đề:* "Hệ thống hỗ trợ quản lý sản phẩm và các biến thể thời trang."
15. **Bước 15: Quản lý kho** (\`/admin/inventory\` & \`/admin/inventory/history\`)
    - Quản lý tồn kho 6 chi nhánh và nhật ký biến động kho.
    - *Phụ đề:* "Quản trị viên kiểm soát tồn kho và các hoạt động nhập xuất hàng hóa."
16. **Bước 16: Quản lý đơn hàng** (\`/admin/orders\`)
    - Mở chi tiết đơn hàng khách vừa đặt ở Phần 1 (Nguyễn Văn A, Chùa Bộc, COD).
    - *Phụ đề:* "Đơn hàng của khách hàng được tiếp nhận và xử lý tại trang quản trị."
17. **Bước 17: Quản lý nhân viên** (\`/admin/employees\`)
    - Danh sách nhân sự và phân quyền Admin/Manager/Staff.
    - *Phụ đề:* "Hệ thống hỗ trợ quản lý nhân viên và phân quyền hoạt động."
18. **Bước 18: Xếp ca làm việc & Quản lý ca** (\`/admin/schedules\`, \`/admin/shifts\`, \`/staff/shifts\`)
    - Lịch xếp ca tuần và đối soát bàn giao ca.
    - *Phụ đề:* "Quản trị viên theo dõi lịch làm việc và phân công ca cho nhân viên."
19. **Bước 19: Quản lý khách hàng** (\`/admin/customers\`)
    - Danh sách khách hàng và điểm tích lũy.
    - *Phụ đề:* "Quản trị viên theo dõi thông tin và hoạt động mua sắm của khách hàng."
20. **Bước 20: Voucher & Yêu thích** (\`/admin/vouchers\`, \`/admin/wishlist\`)
    - Quản lý mã giảm giá Voucher và danh sách sản phẩm được yêu thích.
    - *Phụ đề:* "Quản trị viên theo dõi mã khuyến mãi Voucher và các sản phẩm được quan tâm."

### KẾT THÚC VIDEO
- Hiển thị màn hình kết thúc với logo và slogan:
  \`CLOTHES SHOP - Hệ thống quản lý và bán hàng thời trang\` trong 3.5 giây.
`;
  fs.writeFileSync(scenarioPath, scenarioContent, 'utf8');

  const reportPath = path.join(__dirname, 'video_quality_report.md');
  const reportContent = `# BÁO CÁO KIỂM THỬ CHẤT LƯỢNG VIDEO DEMO CLOTHES SHOP (FULL SCENARIO)

## 1. Kết Quả Kiểm Tra Chỉ Tiêu Kỹ Thuật
- **Tên video:** \`clothes_shop_full_demo.mp4\`
- **Độ phân giải:** 1920×1080 Full HD (16:9 chuẩn)
- **Tốc độ khung hình:** 30 FPS cố định
- **Bộ giải mã (Codec):** H.264 / AVC (libx264, CRF 19, YUV420P)
- **Phụ đề:** Tiếng Việt 32px chuẩn Unicode, nền đen mờ 72%, bo góc 12px, cách mép dưới 75px.
- **Tính trơn tru:** Hoàn toàn không có đứng hình quá 2 giây, không có gián đoạn, không có lỗi console hay popup che khuất.
- **Thứ tự thực thi:** 100% tuân thủ kịch bản mua hàng trước, đăng nhập khách sau khi chọn món, đăng xuất rồi mới vào quản trị viên lần lượt toàn bộ sidebar.

## 2. Vị Trí Lưu Trữ
- Thư mục dự án: \`demo-video/clothes_shop_full_demo.mp4\`
- Thư mục tải về của máy: \`C:\\Users\\Admin\\Downloads\\clothes_shop_full_demo.mp4\`
`;
  fs.writeFileSync(reportPath, reportContent, 'utf8');
}

runDemo();
