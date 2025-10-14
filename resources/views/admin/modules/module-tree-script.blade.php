{{-- Scripts and Styles for Tree --}}
<script type="module">
$(function(){
    const toggles = document.querySelectorAll('.toggle');
    toggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const parentLi = this.closest('li');
            parentLi.classList.toggle('collapsed');
            const icon = this.querySelector('i');
            icon.classList.toggle('bi-chevron-right');
            icon.classList.toggle('bi-chevron-down');
        });
    });
})
</script>
<style>
    .tree,
    .tree ul {
        list-style-type: none;
        padding-left: 2rem;
    }
    .tree li {
        position: relative;
        padding: 0.5rem 0;
    }
    .tree li::before {
        content: "";
        position: absolute;
        top: 0;
        left: -1rem;
        border-left: 1px solid #ccc;
        height: 100%;
        width: 1px;
    }
    .tree li::after {
        content: "";
        position: absolute;
        top: 1.25rem;
        left: -1rem;
        border-top: 1px solid #ccc;
        width: 1rem;
    }
    .tree li:last-child::before {
        height: 1.25rem;
    }
    .tree .toggle {
        cursor: pointer;
    }
    .tree .collapsed ul {
        display: none;
    }
    .tree li button,.tree li a {
        font-size: 12px;
        padding: 2px 6px;
    }
</style>