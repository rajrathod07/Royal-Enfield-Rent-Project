<?php
session_start();
include '../includes/db.php';

// Delete feedback if confirmed
if(isset($_POST['delete_id'])){
    $id = intval($_POST['delete_id']);
    $conn->query("DELETE FROM feedback WHERE id=$id");
}

// Fetch feedbacks with user info
$sql = "SELECT f.id, f.comment, f.created_at, u.name, u.profile_img 
        FROM feedback f 
        JOIN users u ON f.user_id = u.user_id
        ORDER BY f.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Feedback</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
/* 🌌 Dark Gradient Theme */
body { 
background: linear-gradient(278deg, #171616, #17111d, #0c0b0b);
  font-family: "Segoe UI", sans-serif; 
  margin: 20px;
}
h2 { 
  text-align: center; 
  margin-bottom: 30px; 
  color: #fff; 
  font-size: 28px; 
  font-weight: 700; 
  letter-spacing: 1px;
  text-shadow: 0 2px 6px rgba(0,0,0,0.7);
}
.feedback-container { 
  display: flex; 
  flex-wrap: wrap; 
  gap: 25px; 
  justify-content: center; 
}

/* 📝 Feedback Card */
.card { 
  background: #1a1a1a; 
  border: 1px solid #2c2c2c;
  padding: 20px; 
  border-radius: 14px; 
  width: 300px; 
  position: relative; 
  box-shadow: 0 6px 15px rgba(0,0,0,0.5); 
  transition: all 0.3s ease; 
}
.card:hover { 
  transform: translateY(-8px) scale(1.02); 
  box-shadow: 0 10px 25px rgba(0,0,0,0.7);
  border-color: #00adb5;
}
.card img { 
  width: 70px; 
  height: 70px; 
  border-radius: 50%; 
  object-fit: cover; 
  margin-bottom: 12px; 
  border: 2px solid #00adb5;
}
.card h4 { 
  margin: 8px 0; 
  color: #ffd369; 
  font-size: 19px; 
  font-weight: 600;
}
.card p { 
  font-size: 14px; 
  color: #ddd; 
  line-height: 1.6; 
  background: #111; 
  padding: 10px; 
  border-radius: 8px; 
  min-height: 60px; 
}
.card small { 
  color: #aaa; 
  display: block; 
  margin-top: 8px; 
  font-size: 12px;
}

/* 🔙 Back Button */
.back-btn {
    display: inline-block;
    margin-right: 15px;
    padding: 9px 21px;
    background: #1a1a1a;
    color: #e5e5e5;
    border-radius: 19px;
    text-decoration: none;
    font-size: 16px;
    transition: all 0.3s ease;
}
.back-btn:hover {
  background: #000000ff;
  color: #fff;
}
.back-btn i {
  margin-right: 5px;
}


/* ❌ Delete Button */
.delete-btn { 
  position: absolute; 
  top: 12px; 
  right: 12px; 
  color: #ff6b6b; 
  cursor: pointer; 
  font-size: 18px; 
  transition: transform 0.2s, color 0.3s;
}
.delete-btn:hover { 
  transform: rotate(15deg) scale(1.3); 
  color: #ff4444; 
}

/* 🔔 Modal */
.modal { 
  display: none; 
  position: fixed; 
  top:0; left:0; width:100%; height:100%; 
  background: rgba(0,0,0,0.8); 
  justify-content:center; align-items:center; 
  animation: fadeIn 0.3s ease;
}
.modal-content { 
  background: #1f1f1f; 
  padding: 25px; 
  border-radius: 14px; 
  text-align:center; 
  color: #eee; 
  width: 340px; 
  box-shadow: 0 6px 20px rgba(0,0,0,0.6);
  animation: slideIn 0.3s ease;
  border-top: 3px solid #00adb5;
}
.modal-content p { 
  margin-bottom: 15px; 
  font-size: 15px; 
}
.modal-content button { 
  margin: 8px; 
  padding: 8px 16px; 
  border:none; 
  border-radius: 6px; 
  cursor:pointer; 
  font-size: 14px; 
  transition: all 0.3s ease;
}
.modal-content .confirm { 
  background:#ff6b6b; 
  color:#fff; 
}
.modal-content .confirm:hover { 
  background:#ff4444; 
}
.modal-content .cancel { 
  background:#333; 
  color:#fff; 
}
.modal-content .cancel:hover { 
  background:#444; 
}

/* ✨ Animations */
@keyframes fadeIn { from {opacity: 0;} to {opacity: 1;} }
@keyframes slideIn { from {transform: translateY(-20px);} to {transform: translateY(0);} }
</style>
</head>
<body>

    <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
<h2>🌟 All User Feedback</h2>
<div class="feedback-container">
<?php while($row = $result->fetch_assoc()) { ?>
    <div class="card">
        <i class="fas fa-trash delete-btn" onclick="openModal(<?php echo $row['id']; ?>)"></i>
        <img src="../assets/users/<?php echo !empty($row['profile_img']) ? $row['profile_img'] : 'default.png'; ?>" alt="User Photo">
        <h4><?php echo htmlspecialchars($row['name']); ?></h4>
        <p><?php echo nl2br(htmlspecialchars($row['comment'])); ?></p>
        <small><?php echo date("d M Y, H:i", strtotime($row['created_at'])); ?></small>
    </div>
<?php } ?>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-content">
        <p>⚠️ Are you sure you want to delete this comment?</p>
        <form method="post">
            <input type="hidden" name="delete_id" id="delete_id">
            <button type="submit" class="confirm">Yes, Delete</button>
            <button type="button" class="cancel" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
function openModal(id){
    document.getElementById('delete_id').value = id;
    document.getElementById('deleteModal').style.display = 'flex';
}
function closeModal(){
    document.getElementById('deleteModal').style.display = 'none';
}
</script>

</body>
</html>
