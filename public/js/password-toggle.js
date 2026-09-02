document.addEventListener('DOMContentLoaded', function () {
    function bindToggle(input, btn) {
        if (!btn || btn.dataset.bound === '1') {
            return;
        }
        btn.dataset.bound = '1';
        btn.addEventListener('click', function () {
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.innerHTML = show ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
            btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    }

    document.querySelectorAll('input[type="password"]').forEach(function (input) {
        var wrap = input.closest('.password-wrap');
        if (wrap) {
            bindToggle(input, wrap.querySelector('.password-toggle'));
            return;
        }

        wrap = document.createElement('div');
        wrap.className = 'password-wrap';
        input.parentNode.insertBefore(wrap, input);
        wrap.appendChild(input);

        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'password-toggle';
        btn.setAttribute('aria-label', 'Show password');
        btn.innerHTML = '<i class="bi bi-eye"></i>';
        wrap.appendChild(btn);
        bindToggle(input, btn);
    });
});
