<?php

return [
    'userManagement' => [
        'title'          => 'User management',
        'title_singular' => 'User management',
    ],
    'permission' => [
        'title'          => 'Permissions',
        'title_singular' => 'Permission',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'title'             => 'Title',
            'title_helper'      => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'role' => [
        'title'          => 'Roles',
        'title_singular' => 'Role',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'title'              => 'Title',
            'title_helper'       => ' ',
            'permissions'        => 'Permissions',
            'permissions_helper' => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
        ],
    ],
    'user' => [
        'title'          => 'Users',
        'title_singular' => 'User',
        'fields'         => [
            'id'                       => 'ID',
            'id_helper'                => ' ',
            'name'                     => 'Name',
            'name_helper'              => ' ',
            'email'                    => 'Email',
            'email_helper'             => ' ',
            'email_verified_at'        => 'Email verified at',
            'email_verified_at_helper' => ' ',
            'password'                 => 'Password',
            'password_helper'          => ' ',
            'roles'                    => 'Roles',
            'roles_helper'             => ' ',
            'remember_token'           => 'Remember Token',
            'remember_token_helper'    => ' ',
            'created_at'               => 'Created at',
            'created_at_helper'        => ' ',
            'updated_at'               => 'Updated at',
            'updated_at_helper'        => ' ',
            'deleted_at'               => 'Deleted at',
            'deleted_at_helper'        => ' ',
            'id_simpeg'                => 'ID Simpeg',
            'id_simpeg_helper'         => ' ',
            'nip'                      => 'NIP/NIK',
            'nip_helper'               => ' ',
            'no_identitas'             => 'No Identitas',
            'no_identitas_helper'      => ' ',
            'nama'                     => 'Nama',
            'nama_helper'              => ' ',
            'username'                 => 'Username',
            'username_helper'          => ' ',
            'alamat'                   => 'Alamat',
            'alamat_helper'            => ' ',
            'no_hp'                    => 'No Handphone',
            'no_hp_helper'             => ' ',
            'foto_url'                 => 'Foto',
            'foto_url_helper'          => ' ',
            'jwt_token'                => 'Jwt Token',
            'jwt_token_helper'         => ' ',
            'unit'                     => 'Unit',
            'unit_helper'              => ' ',
        ],
    ],
    'userAlert' => [
        'title'          => 'User Alerts',
        'title_singular' => 'User Alert',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'alert_text'        => 'Alert Text',
            'alert_text_helper' => ' ',
            'alert_link'        => 'Alert Link',
            'alert_link_helper' => ' ',
            'user'              => 'Users',
            'user_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
        ],
    ],
    'auditLog' => [
        'title'          => 'Audit Logs',
        'title_singular' => 'Audit Log',
        'fields'         => [
            'id'                  => 'ID',
            'id_helper'           => ' ',
            'description'         => 'Description',
            'description_helper'  => ' ',
            'subject_id'          => 'Subject ID',
            'subject_id_helper'   => ' ',
            'subject_type'        => 'Subject Type',
            'subject_type_helper' => ' ',
            'user_id'             => 'User ID',
            'user_id_helper'      => ' ',
            'properties'          => 'Properties',
            'properties_helper'   => ' ',
            'host'                => 'Host',
            'host_helper'         => ' ',
            'created_at'          => 'Created at',
            'created_at_helper'   => ' ',
            'updated_at'          => 'Updated at',
            'updated_at_helper'   => ' ',
        ],
    ],
    'master' => [
        'title'          => 'Master',
        'title_singular' => 'Master',
    ],
    'lantai' => [
        'title'          => 'Lantai',
        'title_singular' => 'Lantai',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'name'              => 'Name',
            'name_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
            'slug'              => 'Slug',
            'slug_helper'       => ' ',
        ],
    ],
    'ruang' => [
        'title'          => 'Ruang',
        'title_singular' => 'Ruang',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'lantai'            => 'Lantai',
            'lantai_helper'     => ' ',
            'name'              => 'Name',
            'name_helper'       => ' ',
            'kapasitas'         => 'Kapasitas',
            'kapasitas_helper'  => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
            'slug'              => 'Slug',
            'slug_helper'       => ' ',
            'images'            => 'Images',
            'images_helper'     => ' ',
            'fasilitas'         => 'Fasilitas',
            'fasilitas_helper'  => ' ',
        ],
    ],
    'pinjam' => [
        'title'          => 'Peminjaman',
        'title_singular' => 'Peminjaman',
        'fields'         => [
            'id'                     => 'ID',
            'id_helper'              => ' ',
            'ruang'                  => 'Ruang',
            'ruang_helper'           => ' ',
            'time_start'             => 'Waktu Awal',
            'time_start_helper'      => ' ',
            'time_end'               => 'Waktu Akhir',
            'time_end_helper'        => ' ',
            'time_return'            => 'Waktu Pengembalian',
            'time_return_helper'     => ' ',
            'waktu_peminjaman'       => 'Waktu Peminjaman',
            'waktu_peminjaman_helper'=> ' ',
            'penggunaan'             => 'Penggunaan',
            'penggunaan_helper'      => ' ',
            'unit_pengguna'          => 'Unit Pengguna',
            'unit_pengguna_helper'   => ' ',
            'status'                 => 'Status',
            'status_helper'          => ' ',
            'status_text'            => 'Status Text',
            'status_text_helper'     => ' ',
            'borrowed_by'            => 'Pemohon',
            'borrowed_by_helper'     => ' ',
            'processed_by'           => 'Processed By',
            'processed_by_helper'    => ' ',
            'surat_pengajuan'        => 'Surat Pengajuan',
            'surat_pengajuan_helper' => ' ',
            'created_at'             => 'Created at',
            'created_at_helper'      => ' ',
            'updated_at'             => 'Updated at',
            'updated_at_helper'      => ' ',
            'deleted_at'             => 'Deleted at',
            'deleted_at_helper'      => ' ',
            'created_by'             => 'Created By',
            'created_by_helper'      => ' ',
            'no_hp'                  => 'No Handphone',
            'no_hp_helper'           => ' ',
            'tanggal_pengajuan'      => 'Tanggal Pengajuan',
            'tanggal_pengajuan_helper'  => ' ',
        ],
    ],
    'log' => [
        'title'          => 'Log',
        'title_singular' => 'Log',
    ],
    'logPinjam' => [
        'title'          => 'Log Peminjaman',
        'title_singular' => 'Log Peminjaman',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'peminjaman'        => 'Peminjaman',
            'peminjaman_helper' => ' ',
            'jenis'             => 'Jenis',
            'jenis_helper'      => ' ',
            'log'               => 'Log',
            'log_helper'        => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
];
