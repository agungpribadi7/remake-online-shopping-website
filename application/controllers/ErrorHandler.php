<?php
class ErrorHandler extends BaseController {
	/**
	 * Dipanggil ketika user mencoba mengakses halaman yang tidak ada
	 *
	 * @return void
	 */
	public function NotFound() {
		if (file_exists(uri_string()))
			$this->Forbidden();
		else {
			$this->view('errors/404', [
				'title' => '?'
			]);
		}
	}

	/**
	 * Dipanggil ketika user mencoba mengakses halaman yang terlarang
	 *
	 * @return void
	 */
	public function Forbidden() {
		$this->view('errors/403', [
			'title' => ':v'
		]);
	}

}
