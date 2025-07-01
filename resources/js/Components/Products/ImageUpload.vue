<script setup>
import { ref } from "vue";

const props = defineProps({
  productImage: {
    type: [String, File],
    default: null
  }
});

const currentImage = props.productImage ? `/uploads/${props.productImage}` : null;
const preview = ref(currentImage);
const emit = defineEmits(['image']);


const imageSelected = (e) => {
    preview.value = URL.createObjectURL(e.target.files[0]);
    emit('image', e.target.files[0]);
};

const clearImage=() => {
    preview.value = null;
    emit('image', null);
    document.getElementById('image').value = null;
}


</script>

<template>
  <div class="relative inline-block">
    <label for="image">
      <img
        :src="preview ? preview : productImage ? `/uploads/${productImage}` : 'placeholder.png'"
        class=" w-[100px] h-[100px] object-cover"
        alt="Product Image"
      />
    </label>

    <!-- Remove Button (X) -->
    <button
      @click="clearImage"
      type="button"
      class="absolute top-0 right-0 bg-red-600 text-white rounded-full text-xs px-1.5 py-0.5 hover:bg-red-700"
      title="Remove Image"
    >
      ✕
    </button>

    <!-- Hidden file input -->
    <input
      @change="imageSelected($event)"
      type="file"
      id="image"
      class="top-10 left-40 opacity-100 absolute"
    />
  </div>
</template>

