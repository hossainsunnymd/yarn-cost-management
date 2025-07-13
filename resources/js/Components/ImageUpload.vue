<script setup>
import { ref } from "vue";

const props = defineProps({
  productImage: {
    type: [String, File],
    default: null
  }
});

const emit = defineEmits(['image']);

const preview = ref(props.productImage ? `/storage/uploads/${props.productImage}` : null);

const imageSelected = (e) => {
  const file = e.target.files[0];
  if (file) {
    preview.value = URL.createObjectURL(file);
    emit('image', file);
  }
};

const clearImage = () => {
  preview.value = null;
  emit('image', null);
  document.getElementById('image').value = null;
};
</script>

<template>
  <div class="relative inline-block">
    <label for="image">
      <img
        :src="preview ? preview : (productImage ? `/uploads/${productImage}` : 'placeholder.png')"
        class="w-[100px] h-[100px] object-cover border rounded cursor-pointer"
        alt="Product Image"
      />
    </label>

    <!-- Remove Button -->
    <button
      v-if="preview || productImage"
      @click="clearImage"
      type="button"
      class="absolute top-0 right-0 bg-red-600 text-white rounded-full text-xs px-1.5 py-0.5 hover:bg-red-700"
      title="Remove Image"
    >
      ✕
    </button>

    <!-- Hidden input -->
    <input
      @change="imageSelected($event)"
      type="file"
      id="image"
      accept="image/*"
      class="hidden"
    />
  </div>
</template>
