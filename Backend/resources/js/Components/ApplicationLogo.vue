<template>
    <div :class="['brand-logo-wrapper', size]">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="brand-svg">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <span v-if="showName" class="brand-name">SILVER <span class="brand-accent">Docs</span></span>
    </div>
</template>

<script setup lang="ts">
withDefaults(defineProps<{
    showName?: boolean;
    size?: 'sm' | 'md' | 'lg';
}>(), {
    showName: false,
    size: 'md'
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

.brand-logo-wrapper {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    font-family: 'Inter', sans-serif;
}

.brand-icon {
  background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 0 20px rgba(99,102,241,0.4);
}

/* ── Sizes ── */
.sm .brand-icon {
    width: 32px; height: 32px;
    border-radius: 8px;
}
.sm .brand-svg {
    width: 16px; height: 16px;
}
.sm .brand-name {
    font-size: 16px;
}

.md .brand-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
}
.md .brand-svg {
    width: 22px; height: 22px;
}
.md .brand-name {
    font-size: 20px;
}

.lg {
    flex-direction: column;
    gap: 20px;
}
.lg .brand-icon {
    width: 64px; height: 64px;
    border-radius: 16px;
    box-shadow: 0 0 35px rgba(99,102,241,0.5);
}
.lg .brand-svg {
    width: 32px; height: 32px;
}
.lg .brand-name {
    font-size: 32px;
    letter-spacing: -2px;
}

/* ── Colors ── */
.brand-svg {
  color: #fff;
}

.brand-name {
  font-weight: 900;
  color: #ffffff; /* Forced white to match welcome page as requested */
  letter-spacing: -0.3px;
  text-shadow: 0 2px 10px rgba(0,0,0,0.2); /* Added subtle shadow for legibility on light backgrounds if needed */
}

.brand-accent {
  color: #818cf8; /* Light Indigo for the "Docs" part to match coloration */
}
</style>
