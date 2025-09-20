Imports System
Imports System.Windows.Forms
Imports FontAwesome.Sharp        ' NuGet: FontAwesome.Sharp
Public Class Program



    <STAThread>
        Sub Main()
            Application.EnableVisualStyles()
            Application.SetCompatibleTextRenderingDefault(False)
            Application.Run(New frmMain())
        End Sub


End Class