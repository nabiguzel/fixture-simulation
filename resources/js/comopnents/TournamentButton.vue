<template>
    <button @click="$emit('handle-click')" :disabled="isDisabled">
        {{ title }}
    </button>
</template>

<script>
export default {
    name: "tournament-button",
    props: ["title", "isDisabled"],
    emits: ["handle-click"],
};
</script>

<style lang="scss" scoped>
button {
    cursor: pointer;
    background-color: #18a6c5;
    color: #fff;
    padding: 8px 15px;
    margin: 5px;
    border-radius: 5px;
    border: unset;

    &.danger {
        background-color: #b72626;
    }

    &:hover {
        opacity: 0.7;
    }
}
button:disabled,
button[disabled] {
    cursor: not-allowed;
    border: 1px solid #999999;
    background-color: #cccccc;
    color: #666666;

    &:hover {
        opacity: 1;
    }
}
</style>
