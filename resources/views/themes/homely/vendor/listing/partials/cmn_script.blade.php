form.addEventListener('submit', function (e) {
e.preventDefault();

const formData = new FormData(form);
Notiflix.Loading.standard('Saving...');

fetch(postUrl, {
method: 'POST',
headers: {
'X-CSRF-TOKEN': '{{ csrf_token() }}',
},
body: formData
})
.then(async response => {
if (!response.ok) {
const err = await response.json().catch(() => ({}));
throw new Error(err.message || 'Submission failed.');
}
return response.json();
})
.then(data => {
Notiflix.Loading.remove();
if (data.success) {
Notiflix.Notify.success(data.message || 'Saved successfully.');
Notiflix.Loading.standard('Redirecting...');
setTimeout(() => {
window.location.href = data.redirect_url || redirectUrl;
}, 1500);
} else {
Notiflix.Notify.failure(data.message || 'Something went wrong.');
}
})
.catch(error => {
Notiflix.Loading.remove();
console.error(error);
Notiflix.Notify.failure(error.message || 'Something went wrong. Please try again.');
});
});
