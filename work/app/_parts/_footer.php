</main>
<footer>
  <div id="scroll-top" class="scroll-top"><i class="bi bi-arrow-up-circle"></i></div>
  <div class="footer">
    <div class="footer_container">
      <div class="footer_links">
        <a href="howto.php">使い方</a>
        <a href="user_policy.php">利用規約</a>
        <a href="privacy.php">プライバシーポリシー</a>
        <a href="contact.php">お問い合わせ</a>
      </div>
      <small class="copyright">&copy; DreaMuseum</small>
    </div>
  </div>
</footer>
<script>
  const PageTopBtn = document.getElementById('scroll-top');
PageTopBtn.addEventListener('click', () =>{
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});
</script>