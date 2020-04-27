<?php

use yii\db\Migration;
use yii\db\Query;
use yii\db\Command;

/**
 * Class m200425_181448_first
 */
class m200425_181448_first extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function Up()
    {
        $this->dropTable('task_files');
        echo "m200425_181448_first cannot be reverted.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function Down()
    {
        // $this->createTable('task_files', [
        //     'id_task_file' => $this->primaryKey(),
        //     'task_id' => $this->integer()->notNull(),
        //     'file' => $this->string(),
        // ]);

        // $this->addForeignKey(
        //     'fk-task_files-task_id',
        //     'task_files',
        //     'task_id',
        //     'tasks',
        //     'id_task',
        //     'CASCADE'
        // );

        // $sql = "INSERT INTO task_files (task_id, file) VALUES (1, 'test_file_name.pdf');";

        $sql = "
            CREATE TABLE IF NOT EXISTS task_files
            (
                `id_task_file`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `task_id` INT UNSIGNED NOT NULL,
                `file`    VARCHAR(255),
                PRIMARY KEY (id_task_file),
                FOREIGN KEY (task_id) REFERENCES tasks(id_task)
            );
        ";

        $this->execute($sql);

        $sql = "
            INSERT INTO task_files (task_id, file)
            VALUES ('1', 'Robby_project_1.pdf');INSERT INTO task_files (task_id, file)
            VALUES ('2', 'john_project_2.pdf');INSERT INTO task_files (task_id, file)
            VALUES ('3', 'Adel_project_3.pdf');INSERT INTO task_files (task_id, file)
            VALUES ('4', 'Sara_project_4.pdf');INSERT INTO task_files (task_id, file)
            VALUES ('5', 'john_project_2-1.pdf');INSERT INTO task_files (task_id, file)
            VALUES ('6', 'Robby_project_1-1.pdf');INSERT INTO task_files (task_id, file)
            VALUES ('7', 'Robby_project_1.pdf');INSERT INTO task_files (task_id, file)
            VALUES ('8', 'john_project_2.pdf');INSERT INTO task_files (task_id, file)
            VALUES ('9', 'Adel_project_3.doc');INSERT INTO task_files (task_id, file)
            VALUES ('10', 'Sara_project_4.doc');INSERT INTO task_files (task_id, file)
            VALUES ('9', 'john_project_2-1.doc');INSERT INTO task_files (task_id, file)
            VALUES ('8', 'Robby_project_1-1.doc');INSERT INTO task_files (task_id, file)
            VALUES ('7', 'Robby_project_1-1.doc');INSERT INTO task_files (task_id, file)
            VALUES ('1', 'Robby_project_1.psd');INSERT INTO task_files (task_id, file)
            VALUES ('2', 'john_project_2.psd');INSERT INTO task_files (task_id, file)
            VALUES ('3', 'Adel_project_3.psd');INSERT INTO task_files (task_id, file)
            VALUES ('4', 'Sara_project_4.psd');INSERT INTO task_files (task_id, file)
            VALUES ('5', 'john_project_2-1.psd');INSERT INTO task_files (task_id, file)
            VALUES ('6', 'Robby_project_1-1.psd');
        ";

        // $sql .= "INSERT INTO task_files (task_id, file) VALUES (2, 'test_file_name2.pdf');";
        // Yii::$app->db->createCommand('source dump.sql;')->execute();
        $this->execute($sql);

        echo "m200425_181448_first cannot be reverted.\n";

        // return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200425_181448_first cannot be reverted.\n";

        return false;
    }
    */
}
