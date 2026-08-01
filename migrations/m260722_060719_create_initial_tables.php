<?php

use yii\db\Migration;

class m260722_060719_create_initial_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        // 1. User table
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'role' => $this->string(32)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'language' => $this->string(5)->notNull()->defaultValue('uz'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // 2. Company profile table
        $this->createTable('{{%company_profile}}', [
            'id' => $this->integer()->notNull(),
            'company_name' => $this->string()->notNull(),
            'industry' => $this->string(),
            'website' => $this->string(),
            'description' => $this->text(),
            'address' => $this->string(),
            'phone' => $this->string(64),
            'logo' => $this->string(),
        ], $tableOptions);

        $this->addPrimaryKey('pk_company_profile', '{{%company_profile}}', 'id');
        $this->addForeignKey('fk_company_profile_user', '{{%company_profile}}', 'id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        // 3. Scientist profile table
        $this->createTable('{{%scientist_profile}}', [
            'id' => $this->integer()->notNull(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'academic_degree' => $this->string(),
            'institution' => $this->string(),
            'specialization' => $this->string(),
            'bio' => $this->text(),
            'phone' => $this->string(64),
            'cv_file' => $this->string(),
        ], $tableOptions);

        $this->addPrimaryKey('pk_scientist_profile', '{{%scientist_profile}}', 'id');
        $this->addForeignKey('fk_scientist_profile_user', '{{%scientist_profile}}', 'id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        // 4. Category table
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name_uz' => $this->string()->notNull(),
            'name_ru' => $this->string()->notNull(),
            'name_en' => $this->string()->notNull(),
            'description' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // 5. Problem table
        $this->createTable('{{%problem}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'requirements' => $this->text(),
            'budget' => $this->decimal(15, 2),
            'deadline' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10), // 10 = Moderation, 20 = Active, 30 = Solved, 40 = Cancelled, 50 = Spam
            'views_count' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_problem_company', '{{%problem}}', 'company_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_problem_category', '{{%problem}}', 'category_id', '{{%category}}', 'id', 'CASCADE', 'CASCADE');

        // 6. Proposal table
        $this->createTable('{{%proposal}}', [
            'id' => $this->primaryKey(),
            'problem_id' => $this->integer()->notNull(),
            'scientist_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'solution_details' => $this->text()->notNull(),
            'budget_offer' => $this->decimal(15, 2),
            'time_offer' => $this->string(),
            'document_file' => $this->string(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10), // 10 = Submitted, 20 = Evaluating, 30 = Selected, 40 = Rejected
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_proposal_problem', '{{%proposal}}', 'problem_id', '{{%problem}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_proposal_scientist', '{{%proposal}}', 'scientist_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        // 7. Notification table
        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'message' => $this->text()->notNull(),
            'is_read' => $this->boolean()->notNull()->defaultValue(false),
            'link' => $this->string(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_notification_user', '{{%notification}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        // --- SEED SAMPLE DATA ---

        $time = time();
        $security = Yii::$app->security;

        // A. Seed Categories
        $this->batchInsert('{{%category}}', ['name_uz', 'name_ru', 'name_en', 'description', 'created_at', 'updated_at'], [
            [
                'Muhandislik va Konstruksiyalar',
                'Инженерия и Конструкции',
                'Engineering & Structure',
                'Structural analysis, design optimizations, seismic resistance, load calculations.',
                $time, $time
            ],
            [
                'Yashil Qurilish va Materiallar',
                'Зеленое Строительство и Материалы',
                'Green Building & Materials',
                'Eco-friendly concrete, thermal insulation, sustainable composites, energy efficiency.',
                $time, $time
            ],
            [
                'Infratuzilma va Transport',
                'Инфраструктура и Транспорт',
                'Infrastructure & Transport',
                'Roadways, bridges, smart pavement technologies, public transport integration.',
                $time, $time
            ],
            [
                'Geotexnika Muhandisligi',
                'Геотехническая Инженерия',
                'Geotechnical Engineering',
                'Soil mechanics, deep foundations, slope stability, underground structures.',
                $time, $time
            ],
            [
                'Aqlli Qurilish va IoT',
                'Умное Строительство и IoT',
                'Smart Construction & IoT',
                'BIM modeling, sensor integration, drones in surveying, site management automation.',
                $time, $time
            ]
        ]);

        // B. Seed Users
        // Admin
        $this->insert('{{%user}}', [
            'id' => 1,
            'username' => 'admin',
            'email' => 'admin@antigravity.uz',
            'password_hash' => $security->generatePasswordHash('admin123'),
            'auth_key' => $security->generateRandomString(),
            'role' => 'administrator',
            'status' => 10,
            'language' => 'uz',
            'created_at' => $time,
            'updated_at' => $time,
        ]);

        // Company
        $this->insert('{{%user}}', [
            'id' => 2,
            'username' => 'company1',
            'email' => 'company@example.com',
            'password_hash' => $security->generatePasswordHash('company123'),
            'auth_key' => $security->generateRandomString(),
            'role' => 'company',
            'status' => 10,
            'language' => 'uz',
            'created_at' => $time,
            'updated_at' => $time,
        ]);

        $this->insert('{{%company_profile}}', [
            'id' => 2,
            'company_name' => 'Apex Construction LLC',
            'industry' => 'Civil Infrastructure',
            'website' => 'https://apex.example.com',
            'description' => 'A leading infrastructure and civil engineering firm specialized in bridge and road works.',
            'address' => '100 Amir Temur Avenue, Tashkent',
            'phone' => '+998 71 123 45 67',
            'logo' => null,
        ]);

        // Scientist
        $this->insert('{{%user}}', [
            'id' => 3,
            'username' => 'scientist1',
            'email' => 'scientist@example.com',
            'password_hash' => $security->generatePasswordHash('scientist123'),
            'auth_key' => $security->generateRandomString(),
            'role' => 'scientist',
            'status' => 10,
            'language' => 'uz',
            'created_at' => $time,
            'updated_at' => $time,
        ]);

        $this->insert('{{%scientist_profile}}', [
            'id' => 3,
            'first_name' => 'Alisher',
            'last_name' => 'Usmanov',
            'academic_degree' => 'Doctor of Science (DSc)',
            'institution' => 'Tashkent State Technical University',
            'specialization' => 'Innovative Construction Materials & Nanotechnology',
            'bio' => 'Over 15 years of research experience in carbon nanotube-reinforced concrete structures.',
            'phone' => '+998 90 987 65 43',
            'cv_file' => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_notification_user', '{{%notification}}');
        $this->dropTable('{{%notification}}');

        $this->dropForeignKey('fk_proposal_scientist', '{{%proposal}}');
        $this->dropForeignKey('fk_proposal_problem', '{{%proposal}}');
        $this->dropTable('{{%proposal}}');

        $this->dropForeignKey('fk_problem_category', '{{%problem}}');
        $this->dropForeignKey('fk_problem_company', '{{%problem}}');
        $this->dropTable('{{%problem}}');

        $this->dropTable('{{%category}}');

        $this->dropForeignKey('fk_scientist_profile_user', '{{%scientist_profile}}');
        $this->dropTable('{{%scientist_profile}}');

        $this->dropForeignKey('fk_company_profile_user', '{{%company_profile}}');
        $this->dropTable('{{%company_profile}}');

        $this->dropTable('{{%user}}');
    }
}
