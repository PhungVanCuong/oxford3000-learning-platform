<template>
  <section class="learning-content-section py-4 py-lg-5" :class="customClass">
    <div class="container max-w-7xl">
      <!-- Section Header -->
      <div class="d-flex align-items-center justify-content-between mb-3 mb-md-4">
        <h2 class="learning-section-title text-white fw-black mb-0">
          Chọn nội dung học
        </h2>
      </div>

      <!-- 3 Columns of Learning Content Cards -->
      <div class="row g-3 g-md-4">
        <!-- CARD 1: Ngữ pháp theo bậc CEFR -->
        <div class="col-12 col-md-4">
          <router-link
            to="/grammar"
            class="learning-content-card h-100 d-flex flex-column text-decoration-none transition-all position-relative"
          >
            <!-- Card Image Container -->
            <div class="learning-card-art mb-3 overflow-hidden rounded-3 position-relative">
              <img
                src="/images/learning-content/grammar-card.png"
                alt="Ngữ pháp theo bậc CEFR"
                class="w-100 object-fit-cover transition-transform"
                loading="lazy"
              />
            </div>

            <!-- Card Heading -->
            <h3 class="learning-card-title text-white fw-black mb-2">
              Ngữ pháp theo bậc CEFR
            </h3>

            <!-- Card Description -->
            <p class="learning-card-desc mb-4">
              101 bài trải đều sáu bậc từ A1 tới C2. Mỗi bài có phần lý thuyết ngắn gọn rồi tới bài kiểm tra ở ba mức Dễ, Vừa và Khó.
            </p>

            <!-- Card Action Arrow Button (Góc dưới cùng bên phải) -->
            <div class="learning-card-action mt-auto text-end">
              <span class="card-arrow-circle d-inline-flex align-items-center justify-content-center">
                <i class="bi bi-arrow-right"></i>
              </span>
            </div>
          </router-link>
        </div>

        <!-- CARD 2: Từ vựng theo chủ đề -->
        <div class="col-12 col-md-4">
          <router-link
            to="/curriculum"
            class="learning-content-card h-100 d-flex flex-column text-decoration-none transition-all position-relative"
          >
            <!-- Card Image Container -->
            <div class="learning-card-art mb-3 overflow-hidden rounded-3 position-relative">
              <img
                src="/images/learning-content/topic-card.png"
                alt="Từ vựng theo chủ đề"
                class="w-100 object-fit-cover transition-transform"
                loading="lazy"
              />
            </div>

            <!-- Card Heading -->
            <h3 class="learning-card-title text-white fw-black mb-2">
              Từ vựng theo chủ đề
            </h3>

            <!-- Card Description -->
            <p class="learning-card-desc mb-4">
              Hơn 5.000 từ học thuật chia theo 25 chủ đề IELTS. Học bằng thẻ từ kèm nghĩa tiếng Việt, ví dụ và cách phát âm.
            </p>

            <!-- Card Action Arrow Button -->
            <div class="learning-card-action mt-auto text-end">
              <span class="card-arrow-circle d-inline-flex align-items-center justify-content-center">
                <i class="bi bi-arrow-right"></i>
              </span>
            </div>
          </router-link>
        </div>

        <!-- CARD 3: Từ vựng theo bậc CEFR -->
        <div class="col-12 col-md-4">
          <router-link
            to="/cefr"
            class="learning-content-card h-100 d-flex flex-column text-decoration-none transition-all position-relative"
          >
            <!-- Card Image Container -->
            <div class="learning-card-art mb-3 overflow-hidden rounded-3 position-relative">
              <img
                src="/images/learning-content/cefr-card.png"
                alt="Từ vựng theo bậc CEFR"
                class="w-100 object-fit-cover transition-transform"
                loading="lazy"
              />
            </div>

            <!-- Card Heading -->
            <h3 class="learning-card-title text-white fw-black mb-2">
              Từ vựng theo bậc CEFR
            </h3>

            <!-- Card Description -->
            <p class="learning-card-desc mb-4">
              Từ vựng nền tảng theo chuẩn CEFR, sắp xếp theo sáu bậc A1 đến C2, hợp cho người mới bắt đầu xây gốc từ vựng.
            </p>

            <!-- Card Action Arrow Button -->
            <div class="learning-card-action mt-auto text-end">
              <span class="card-arrow-circle d-inline-flex align-items-center justify-content-center">
                <i class="bi bi-arrow-right"></i>
              </span>
            </div>
          </router-link>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
defineProps({
  customClass: {
    type: String,
    default: ''
  }
})
</script>

<style scoped>
.learning-content-section {
  background-color: #121c1b;
  border-radius: 24px;
}

.learning-section-title {
  font-size: 1.85rem;
  letter-spacing: -0.5px;
  line-height: 1.2;
}

@media (min-width: 992px) {
  .learning-section-title {
    font-size: 2.15rem;
  }
}

/* Thẻ Học Tập chuẩn phong cách Dark Teal chuyên nghiệp */
.learning-content-card {
  background-color: #173a3c;
  border: 1px solid rgba(0, 176, 116, 0.22);
  border-radius: 20px;
  padding: 16px;
  cursor: pointer;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

@media (min-width: 768px) {
  .learning-content-card {
    padding: 20px;
    border-radius: 22px;
  }
}

.learning-content-card:hover {
  transform: translateY(-6px);
  background-color: #1b4547;
  border-color: #00b074;
  box-shadow: 0 16px 36px rgba(0, 0, 0, 0.45), 0 0 24px rgba(0, 176, 116, 0.25);
}

.learning-card-art {
  background-color: #112d2f;
  aspect-ratio: 324 / 295;
}

.learning-card-art img {
  height: 100%;
  display: block;
  transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.learning-content-card:hover .learning-card-art img {
  transform: scale(1.03);
}

.learning-card-title {
  font-size: 1.28rem;
  letter-spacing: -0.3px;
  line-height: 1.25;
}

.learning-card-desc {
  color: #a3c4be;
  font-size: 0.86rem;
  line-height: 1.55;
}

/* Nút Mũi Tên Nhỏ Xinh Bo Tròn Góc Phải Dưới Cùng */
.card-arrow-circle {
  width: 32px;
  height: 32px;
  background-color: rgba(0, 176, 116, 0.15);
  border: 1px solid #00b074;
  border-radius: 50%;
  color: #5eead4;
  font-size: 15px;
  transition: all 0.25s ease;
}

.learning-content-card:hover .card-arrow-circle {
  background-color: #00b074;
  color: #ffffff;
  transform: translateX(3px) scale(1.08);
  box-shadow: 0 0 12px rgba(0, 176, 116, 0.6);
}
</style>
