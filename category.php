<?php
    $page_title  ='Category Details';
    require_once 'includes/transaction_setup.php';
    require_once 'includes/config.php';

    $user = new User();

    //redirect if user is not logged in
    if(!$user->loggedIn()){
        redirect('index.php');
    }
    
    if(key_exists('new', $_POST) or key_exists('new', $_GET)){
        if (!$user->isTreasurer()){
            echo "<script>alert('You must have treasurer privileges to create a category')</script>";
        } else {
            $result = mysql_query("SELECT MAX(ID) AS ID FROM Category") or die(mysql_error());
            $newID = mysql_fetch_array($result)['ID'] + 1;
            $sql="INSERT INTO Category (Name, Description) VALUES ('New Category (".$newID.")','')";
            mysql_query($sql) or die("Category cannot be created: ".mysql_error());
        }

        $id = mysql_insert_id();
    } else {
        $id=(key_exists('id', $_GET)) ? $_GET["id"] : die("No category specified");
    }    

    //Check ID is valid
    $sql = "SELECT Name, Description FROM Category WHERE ID=" . $id . "";
    $result = mysql_query($sql) or die("Category.ID not specified correctly: ".mysql_error());
    if(!$result) die("No categories in database match given ID: ".$id);
    $fetch = mysql_fetch_array($result);

    //If delete button was pressed
    if(!empty($_POST) && key_exists('delete', $_POST))
	{
		if (!$user->isTreasurer()){
            echo "<script>alert('You must have treasurer privileges to delete a category!You are going to be redirected to the main page')</script>";
			echo "<meta http-equiv='Refresh' content='0; URL=search.php'>";
        } else {
			$canDelete = TRUE;
			// Check if it can perform delete action
			$sql = "SELECT id FROM SubCategory WHERE CategoryID ='". $_GET['id']."'";
			$SubCatList = mysql_query($sql);

			// For each subcategory of this category
			while($SubCatListIDs = mysql_fetch_array($SubCatList))
			{
				// Check if there is any Categorization associated with it
				$sql = "SELECT HistoryID FROM Categorization WHERE SubCategoryID = '". $SubCatListIDs['id'] ."'";
				$CategList = mysql_query($sql);

				while($CategListIDs = mysql_fetch_array($CategList))
				{
					// If it exists at least one matching on Categorization table
					if ($CategListIDs != FALSE)
					{
						// Can't delete
						$canDelete = FALSE;
					}
				}
			}
			// If you can delete
			if ($canDelete)
			{
				$sql = "SELECT id FROM SubCategory WHERE CategoryID ='". $_GET['id']."'";
				$SubCatList = mysql_query($sql);

				// Delete all subcategories first
				while($SubCatListIDs = mysql_fetch_array($SubCatList))
				{
					$sql="DELETE FROM subcategory WHERE subcategory.ID ='". $SubCatListIDs['id'] ."'";
					mysql_query($sql) or die("cannot delete category: ".mysql_error());
				}

				// Delete the main category
				$sql="DELETE FROM category WHERE category.ID ='". $_GET['id']."'";
				mysql_query($sql) or die("cannot delete category: ".mysql_error());

				echo "<script>alert('Category and Subcategories were successfully deleted')</script>";
				echo "<meta http-equiv='Refresh' content='0; URL=search.php'>";
			}
			else{
				// Else you can't delete!
				echo "<script>alert('ERROR: It is not possible delete any category which is associated with a transaction.')</script>";
			}
        }
    }


    //Does the ID Correspond to a valid category?
    $sql = "SELECT Name, Description FROM category WHERE ID=".$id;
    $result = mysql_query($sql) or die("Category.ID not specified correctly: ".mysql_error());
    if(!$result) die("No categories in database match given ID: ".mysql_error());
    $row = mysql_fetch_array($result);

    $name = $row['Name'];
    $desc = $row['Description'];

    //If the save button was pressed
    if(!empty($_POST) && key_exists('save', $_POST)){
        if(!key_exists('name', $_POST)) {
            echo "<script>alert('No name specified in $_POST')</script>";
        }
        // if(!key_exists('desc', $_POST)){
            // echo "<script>alert('No desc specified in $_POST')</script>";
        // }
        else if($_POST['name'] == ""){
            echo "<script>alert('Name must not be empty')</script>";
        } else If(!$user->isTreasurer()){
            echo "<script>alert('You must have treasurer privileges to modify a category')</script>";
			redirect("search.php");
        } else {
            $name = $_POST['name'];
            $desc = $_POST['desc'];
			
			// Check if there is any Categorization associated with it
			$sql = "SELECT Name FROM Category WHERE Name = '". $name ."'";
			$CategList = mysql_query($sql);
			$canDelete = TRUE;
			
			while($CategListNames = mysql_fetch_array($CategList))
			{
				// If it exists at least one matching on Categorization table
				if ($CategListNames != FALSE)
				{
					// Can't delete
					$canDelete = FALSE;
				}
			}
			if ($canDelete) {
				mysql_query("UPDATE category SET Name='".$name."', Description='".$desc."' ".
							"WHERE category.ID=".$id) or die("".mysql_error());
				echo "<script>alert('Successfully updated category')</script>";
			} else {
				echo "<script>alert('ERROR: This name already exists on database. Please specify other.')</script>";
			}
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $page_title ?></title>
        <link rel="stylesheet" type="text/css" href="css/style2.css">
        <link rel="stylesheet" type="text/css" href="css/styling.css">
    </head>

    <body id="main">
		<div id="box">
			<?php include 'subheader.php' ?>

			<form action="category.php?id=<?php echo $id; ?>" method="post" id="content" >
                <input type="hidden" name="id" value=>

                <b>* This field is compulsory</b>
				<br><br>
                <table>
                    <tr>
                        <td class = "spaceBelow">Name*:</td>
                        <td><input type="text" name="name" class="data" value="<?php echo $name ?>"></td>
                    </tr>
                    <tr>
                        <td>Description:</td>
                        <td></td>
                    </tr>
                </table>
                <textarea name="desc" class="data"><?php echo $desc ?></textarea>

				<br><br>

                <input type="submit" name="save" value="Save">
                <input type="submit" name="delete" value="Delete">
                <input type="submit" name="new" value="New">

				<br><br>

				<h4>Subcategory List</h4>
				<table border="1">
					<tr>
						<th>Name</th>
						<th>Description</th>
					</tr>
					<?php
						$sql = mysql_query("SELECT * FROM Subcategory WHERE CategoryID = '". $_GET['id']."' ORDER BY Name ASC");
						// For each row of Category
						while ($row = mysql_fetch_array($sql)) {
							echo "<tr>";
							echo "<td>". $row["Name"] ."</td>";
							echo "<td>". $row["Description"] ."</td>";
							echo "<td><a href='subcategory.php?id=".$row['ID']."'>Edit</a></td>";
							echo "</tr>";
						}
					?>
				</table>

            </form>
            <!--<form action="categoryDelete.php" method="get">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <input type="submit" value="Delete">
            </form>
            <button onclick="location.href='categoryDelete.php?id=<?php echo $id ?>'">Delete</button> -->
        </div><!-- end box-->
        <div id="sidebar">
				<?php include_once("sidebar.php");?>
        </div><!-- end sidebar -->
	</body>

</html>
