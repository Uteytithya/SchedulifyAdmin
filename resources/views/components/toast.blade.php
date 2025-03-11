@if($message)
    <script>
        Swal.fire({
            icon: '{{ $type }}',
            title: '{{ $message }}',
            toast: true,
            position: '{{ $position }}',
            showConfirmButton: false,  
            showCloseButton: true,  
            timerProgressBar: true,
            background: '{{ $color }}',
            customClass: {
                popup: 'rounded-lg shadow-lg flex items-center ',
                title: 'text-lg font-medium flex-grow',
                closeButton: 'swal2-close text-blue-200 ml-2' 
            },
            timer: 3000
        });
    </script>
@endif
