<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTreeFieldsCategoriesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('categories', function(Blueprint $table) {
      // These columns are needed for Baum's Nested Set implementation to work.
      // Column names may be changed, but they *must* all exist and be modified
      // in the model.
      // Take a look at the model scaffold comments for details.
      // We add indexes on parent_id, lft, rgt columns by default.
      $table->integer('parent_id')->nullable()->index();
      $table->integer('lft')->nullable()->index();
      $table->integer('rgt')->nullable()->index();
      $table->integer('depth')->nullable();

      // Add needed columns here (f.ex: name, slug, path, etc.)
      // $table->string('name', 255);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
      Schema::table('categories', function(Blueprint $table) {
          // These columns are needed for Baum's Nested Set implementation to work.
          // Column names may be changed, but they *must* all exist and be modified
          // in the model.
          // Take a look at the model scaffold comments for details.
          // We add indexes on parent_id, lft, rgt columns by default.
          $table->dropColumn(['parent_id', 'lft', 'rgt', 'depth']);

          // Add needed columns here (f.ex: name, slug, path, etc.)
          // $table->string('name', 255);
      });
  }

}
