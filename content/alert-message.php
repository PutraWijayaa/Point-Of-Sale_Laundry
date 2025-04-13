<?php if (isset($_GET['alert']) && isset($_GET['message'])): ?>
<script>
    Swal.fire({
        icon: '<?php echo $_GET['alert'] === 'success' ? 'success' : ($_GET['alert'] === 'error' ? 'error' : 'info'); ?>',
        title: '<?php echo ucfirst($_GET['alert']); ?>!',
        text: '<?php echo htmlspecialchars($_GET['message'], ENT_QUOTES); ?>',
        confirmButtonColor: '#3085d6'
    }).then(() => {
        const url = new URL(window.location.href);
        url.searchParams.delete('alert');
        url.searchParams.delete('message');
        window.location.href = url.toString();
    });
</script>
<?php endif; ?>
